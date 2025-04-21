<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $categories;
    public $category_id = '';
    public $images = [];
    public $previews = [];
    public $product = [
        'sku' => '',
        'name' => '',
        'description' => '',
        'image_path' => '',
        'purchase_price' => '',
        'sale_price' => '',
        'min_stock' => 0,
        'subcategory_id' => '',
        'related_products' => [],
    ];

    public $related_products = [];

    public $availableProducts = [];
    public $searchTerm = '';

    public function mount()
    {
        $this->categories = Category::with('subcategories')->get();
        $this->loadAvailableProducts();
    }

    #[Computed()]
    public function subcategories()
    {
        if (!$this->category_id) {
            return collect();
        }
        return Subcategory::where('category_id', $this->category_id)->get();
    }

    public function updatedSearchTerm()
    {
        $this->loadAvailableProducts();
    }

    public function updatedProductSubcategoryId()
    {
        $this->loadAvailableProducts();
    }

    protected function loadAvailableProducts()
    {
        $query = Product::query()
            ->where('id', '!=', $this->product['id'] ?? 0) // Excluir el producto actual si es edición
            ->limit(20);

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->product['subcategory_id']) {
            // Opcional: cargar productos de la misma subcategoría primero
            $query->orderByRaw('CASE WHEN subcategory_id = ? THEN 0 ELSE 1 END', [$this->product['subcategory_id']]);
        }

        $this->availableProducts = $query->get();
    }

    public function addRelatedProduct($productId)
    {
        if (count($this->related_products) >= 8) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Límite alcanzado',
                'text' => 'Solo puedes seleccionar hasta 8 productos relacionados',
            ]);
            return;
        }

        if (!in_array($productId, $this->related_products)) {
            $this->related_products[] = $productId;
            $this->loadAvailableProducts();
        }
    }

    public function removeRelatedProduct($index)
    {
        unset($this->related_products[$index]);
        $this->related_products = array_values($this->related_products);
    }

    public function updatedImages()
    {
        $this->validate([
            'images.*' => 'image|max:2048', // 2MB max
        ]);
        $this->previews = collect($this->images)->map(fn($img) => $img->temporaryUrl())->toArray();
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        unset($this->previews[$index]);
        // Reindex arrays
        $this->images = array_values($this->images);
        $this->previews = array_values($this->previews);
    }

    public function store()
    {
        $this->validate([
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => 'image|max:2048',
            'product.sku' => 'required|unique:products,sku',
            'product.name' => 'required|max:255',
            'product.description' => 'nullable|string',
            'product.purchase_price' => 'required|numeric|min:0',
            'product.sale_price' => 'required|numeric|min:' . $this->product['purchase_price'],
            'product.subcategory_id' => 'required|exists:subcategories,id',
            'product.min_stock' => 'required|integer|min:0',
            'product.related_products' => 'array|max:8',
            'product.related_products.*' => 'exists:products,id',
        ],[],[
            'product.sku' => 'código',
            'product.name' => 'nombre',
            'product.description' => 'descripción',
            'product.purchase_price' => 'precio de compra',
            'product.sale_price' => 'precio de venta',
            'product.subcategory_id' => 'subcategoría',
            'product.min_stock' => 'stock mínimo',
        ]);

        try {
            // Guardar imágenes
            $imagePaths = collect($this->images)->map(function ($image) {
                return $image->store('products', 'public');
            })->toArray();

            $this->product['image_path'] = json_encode($imagePaths);
            $this->product['related_products'] = $this->related_products ?? [];
            $this->product['related_products'] = json_encode($this->product['related_products']);

            // dump($this->product['related_products'], $this->related_products);

            // Crear producto
            $product = Product::create($this->product);

            session()->flash('swal', [
                'title' => 'Producto creado',
                'message' => 'El producto se ha creado correctamente',
                'icon' => 'success'
            ]);

            return redirect()->route('admin.products.edit', $product);
        } catch (\Exception $e) {
            $this->dispatch('error', [
                'title' => 'Error al crear producto',
                'message' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.products.product-create');
    }
}
