<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Collection;

class ProductCarousel extends Component
{
    public $category;
    public $subcategory;
    public $currentProduct;
    public $products = [];
    
    public function mount(Product $product)
    {
        $this->currentProduct = $product;
        $this->category = $product->subcategory->category;
        $this->subcategory = $product->subcategory;
        
        $relatedProducts = $this->getRelatedProducts($product);
        $subcategoryProducts = $this->getSubcategoryProducts($product, $relatedProducts);
        $categoryProducts = $this->getCategoryProducts($product, $relatedProducts, $subcategoryProducts);
        
        // Combinar todos los productos y limitar a 8
        $this->products = $relatedProducts
            ->merge($subcategoryProducts)
            ->merge($categoryProducts)
            ->take(8);
    }

    protected function getRelatedProducts(Product $product): Collection
    {
        // Obtener IDs de productos relacionados
        $relatedIds = $product->related_products ?? [];
        if (!is_array($relatedIds)) {
            $relatedIds = json_decode($relatedIds, true) ?? [];
        }

        return Product::whereIn('id', $relatedIds)
            ->where('id', '!=', $product->id)
            ->where('is_enabled', 1)
            ->get();
    }

    protected function getSubcategoryProducts(Product $product, Collection $relatedProducts): Collection
    {
        $remainingCount = max(0, 8 - $relatedProducts->count());
        if ($remainingCount <= 0) {
            return collect();
        }

        return Product::where('subcategory_id', $product->subcategory_id)
            ->where('id', '!=', $product->id)
            ->whereNotIn('id', $relatedProducts->pluck('id'))
            ->where('is_enabled', 1)
            ->inRandomOrder()
            ->take($remainingCount)
            ->get();
    }

    protected function getCategoryProducts(Product $product, Collection $relatedProducts, Collection $subcategoryProducts): Collection
    {
        $remainingCount = max(0, 8 - $relatedProducts->count() - $subcategoryProducts->count());
        if ($remainingCount <= 0) {
            return collect();
        }

        return Product::whereHas('subcategory', function ($query) use ($product) {
                $query->where('category_id', $product->subcategory->category_id);
            })
            ->where('id', '!=', $product->id)
            ->whereNotIn('id', $relatedProducts->pluck('id'))
            ->whereNotIn('id', $subcategoryProducts->pluck('id'))
            ->where('is_enabled', 1)
            ->inRandomOrder()
            ->take($remainingCount)
            ->get();
    }

    public function render()
    {
        return view('livewire.products.product-carousel');
    }
}