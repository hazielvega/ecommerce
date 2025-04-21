<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductPrices extends Component
{
    public $categories = [];
    public $selected_category = "";
    public $selected_subcategory = "";
    public $subcategories = [];
    public $adjustment_type = "increase";
    public $percentage;
    public $showConfirmation = false;

    protected $listeners = ['priceChangeApplied' => 'resetForm'];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function updatedSelectedCategory($value)
    {
        $this->subcategories = $value 
            ? Category::find($value)->subcategories 
            : collect([]);
        $this->selected_subcategory = "";
    }

    public function confirmPriceChange()
    {
        $this->validate([
            'percentage' => 'required|numeric|min:1|max:99',
        ], [], [
            'percentage' => 'porcentaje',
        ]);

        $this->showConfirmation = true;
    }

    public function applyPriceChange()
    {
        DB::transaction(function () {
            $query = Product::query();
        
            if ($this->selected_category) {
                $query->whereHas('subcategory', function($q) {
                    $q->where('category_id', $this->selected_category);
                });
            }
        
            if ($this->selected_subcategory) {
                $query->where('subcategory_id', $this->selected_subcategory);
            }
        
            $products = $query->get();
        
            foreach ($products as $product) {
                $factor = $this->adjustment_type === 'increase' 
                    ? (1 + $this->percentage / 100) 
                    : (1 - $this->percentage / 100);
                
                $newPrice = round($product->sale_price * $factor, 2);
                $product->update(['sale_price' => max($newPrice, 0.01)]);
        
                foreach ($product->variants as $variant) {
                    $variantNewPrice = round($variant->sale_price * $factor, 2);
                    $variant->update(['sale_price' => max($variantNewPrice, 0.01)]);
                }
            }
        });

        $this->showConfirmation = false;
        $this->resetForm();
        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Ajuste aplicado',
            'message' => 'Los precios se han actualizado correctamente.'
        ]);

        return redirect()->route('admin.products.index');
    }

    public function resetForm()
    {
        $this->reset(['selected_category', 'selected_subcategory', 'adjustment_type', 'percentage']);
        $this->subcategories = collect([]);
    }

    public function render()
    {
        return view('livewire.admin.products.product-prices');
    }
}