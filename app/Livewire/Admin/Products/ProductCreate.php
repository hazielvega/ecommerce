<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $categories, $category_id = '', $images = [], $previews = [];
    public $product = [
        'sku' => '',
        'name' => '',
        'description' => '',
        'image_path' => '',
        'purchase_price' => '',
        'sale_price' => '',
        'subcategory_id' => '',
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    #[Computed()]
    public function subcategories()
    {
        return Subcategory::where('category_id', $this->category_id)->get();
    }

    // PREVISUALIZAR IMÁGENES
    public function updatedImages()
    {
        $this->previews = collect($this->images)->map(fn($img) => $img->temporaryUrl())->toArray();
    }

    // ELIMINAR UNA IMAGEN SELECCIONADA
    public function removeImage($index)
    {
        array_splice($this->images, $index, 1);
        array_splice($this->previews, $index, 1);
    }

    public function store()
    {
        $this->validate([
            'images.*' => 'required|image|max:1024',
            'product.sku' => 'required|unique:products,sku',
            'product.name' => 'required|max:255',
            'product.purchase_price' => 'required|numeric|min:0',
            'product.sale_price' => 'required|numeric|min:0',
            'product.subcategory_id' => 'required|exists:subcategories,id',
        ]);

        // GUARDAR LAS IMÁGENES
        $imagePaths = collect($this->images)->map(fn($image) => $image->store('products'))->toJson();
        $this->product['image_path'] = $imagePaths;

        // CREAR EL PRODUCTO
        Product::create($this->product);

        // MENSAJE DE ÉXITO
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'Producto agregado correctamente',
        ]);

        return redirect()->route('admin.products.edit', Product::latest()->first());
    }

    public function render()
    {
        return view('livewire.admin.products.product-create');
    }
}

