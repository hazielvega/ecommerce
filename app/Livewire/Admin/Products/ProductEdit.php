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

class ProductEdit extends Component
{
    use WithFileUploads;

    public $categories;
    public $category_id = '';
    public $images = [];
    public $previews = [];
    public $existingImages = [];
    public $product;
    public $related_products = [];
    public $availableProducts = [];
    public $searchTerm = '';

    public function mount(Product $product)
    {
        $this->categories = Category::with('subcategories')->get();
        $this->product = $product->toArray();
        $this->product['is_enabled'] = $product->is_enabled;
        $this->product['has_enabled_variants'] = $product->variants()->where('is_enabled', true)->exists();

        // Cargar categoría y subcategoría
        $productModel = Product::with('subcategory.category')->find($product->id);
        $this->category_id = $productModel->subcategory->category_id ?? '';

        // Cargar imágenes existentes
        $this->existingImages = json_decode($product->image_path, true) ?? [];

        // Cargar productos relacionados
        $this->related_products = json_decode($product->related_products, true) ?? [];

        $this->loadAvailableProducts();
    }

    public function validateBeforeEnable()
    {
        $product = Product::withCount(['variants' => function ($query) {
            $query->where('is_enabled', true);
        }])->find($this->product['id']);

        // Si el producto está actualmente habilitado, podemos deshabilitarlo sin validaciones
        if ($product->is_enabled) {
            $this->disableProduct($product);
            return;
        }

        // Validar que tenga al menos una variante habilitada para poder habilitar el producto
        if ($product->variants_count < 1) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'No se puede habilitar',
                'text' => 'El producto debe tener al menos una variante habilitada para poder activarlo.',
                // 'footer' => '<a href="' . route('admin.products.variants', $product->id) . '" class="text-indigo-400 hover:underline">Configurar variantes</a>'
            ]);
            return;
        }

        $this->enableProduct($product);
    }

    protected function enableProduct(Product $product)
    {
        try {
            $product->update(['is_enabled' => true]);

            $this->product['is_enabled'] = true;

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Producto habilitado',
                'text' => 'El producto ha sido habilitado correctamente.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al habilitar',
                'text' => 'Ocurrió un error al intentar habilitar el producto: ' . $e->getMessage(),
            ]);
        }
    }

    protected function disableProduct(Product $product)
    {
        try {
            $product->update(['is_enabled' => false]);

            $this->product['is_enabled'] = false;

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Producto deshabilitado',
                'text' => 'El producto ha sido deshabilitado correctamente.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al deshabilitar',
                'text' => 'Ocurrió un error al intentar deshabilitar el producto: ' . $e->getMessage(),
            ]);
        }
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
            ->where('id', '!=', $this->product['id'])
            ->limit(20);

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->product['subcategory_id']) {
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
            // $this->loadAvailableProducts();
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
        $this->images = array_values($this->images);
        $this->previews = array_values($this->previews);
    }

    public function removeExistingImage($index)
    {
        // Eliminar la imagen del servidor
        Storage::disk('public')->delete($this->existingImages[$index]);

        // Eliminar la imagen del array
        unset($this->existingImages[$index]);
        $this->existingImages = array_values($this->existingImages);
    }

    public function update()
    {
        $this->validate([
            'images.*' => 'image|max:2048',
            'product.sku' => 'required|unique:products,sku,' . $this->product['id'],
            'product.name' => 'required|max:255',
            'product.description' => 'nullable|string',
            'product.purchase_price' => 'required|numeric|min:0',
            'product.sale_price' => 'required|numeric|min:' . $this->product['purchase_price'],
            'product.subcategory_id' => 'required|exists:subcategories,id',
            'product.min_stock' => 'required|integer|min:0',
            // 'product.related_products' => 'array|max:8',
            // 'product.related_products.*' => 'exists:products,id',
        ], [], [
            'product.sku' => 'código',
            'product.name' => 'nombre',
            'product.description' => 'descripción',
            'product.purchase_price' => 'precio de compra',
            'product.sale_price' => 'precio de venta',
            'product.subcategory_id' => 'subcategoría',
            'product.min_stock' => 'stock mínimo',
        ]);
        try {
            // Guardar nuevas imágenes
            $newImagePaths = collect($this->images)->map(function ($image) {
                return $image->store('products', 'public');
            })->toArray();

            // Combinar imágenes existentes con las nuevas
            $allImages = array_merge($this->existingImages, $newImagePaths);

            // Actualizar producto
            $product = Product::find($this->product['id']);
            $product->update([
                'sku' => $this->product['sku'],
                'name' => $this->product['name'],
                'description' => $this->product['description'],
                'image_path' => json_encode($allImages),
                'purchase_price' => $this->product['purchase_price'],
                'sale_price' => $this->product['sale_price'],
                'min_stock' => $this->product['min_stock'],
                'subcategory_id' => $this->product['subcategory_id'],
                'related_products' => json_encode($this->related_products),
            ]);

            session()->flash('swal', [
                'title' => 'Producto actualizado',
                'message' => 'El producto se ha actualizado correctamente',
                'icon' => 'success'
            ]);

            return redirect()->route('admin.products.edit', $product);
        } catch (\Exception $e) {
            $this->dispatch('error', [
                'title' => 'Error al actualizar producto',
                'message' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.products.product-edit');
    }
}
