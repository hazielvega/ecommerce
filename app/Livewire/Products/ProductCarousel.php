<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ProductCarousel extends Component
{
    public $category; // Categoría del producto actual
    public $product; // Producto que se está mostrando
    public $products; // Productos a mostrar en el carrusel

    public function mount($category, $product)
    {
        $this->products = Product::whereHas('subcategory', function ($query) use ($category) {
            $query->where('category_id', $category->id);
        })
        ->where('id', '!=', $product->id) // Excluir el producto actual
        ->get();
    
        // dd($this->products);
    }
    


    public function render()
    {
        return view('livewire.products.product-carousel');
    }
}
