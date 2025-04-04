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
    public $adjustment_type = "increase";
    public $percentage;

    protected $listeners = ['applyPriceChange' => 'applyPriceChange'];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function getSubcategoriesProperty()
    {
        return $this->selected_category
            ? Category::find($this->selected_category)?->subcategories ?? collect([])
            : collect([]);
    }

    public function confirmPriceChange()
    {
        $this->validate([
            'percentage' => 'required|numeric|min:1|max:99',
        ],[],[
            'percentage' => 'porcentaje',
        ]);

        $this->dispatch('confirmPriceChange'); // Evento para confirmar en el frontend
    }

    public function applyPriceChange()
    {
        DB::transaction(function () {
            $query = Product::query();
        
            // Si se seleccionó una categoría, obtener sus subcategorías
            if ($this->selected_category) {
                $subcategories = Category::find($this->selected_category)?->subcategories->pluck('id')->toArray();
        
                if (!empty($subcategories)) {
                    $query->whereIn('subcategory_id', $subcategories);
                }
            }
        
            if ($this->selected_subcategory) {
                $query->where('subcategory_id', $this->selected_subcategory);
            }
        
            $products = $query->get();
        
            foreach ($products as $product) {
                $factor = $this->adjustment_type === 'increase' ? (1 + $this->percentage / 100) : (1 - $this->percentage / 100);
                
                $newPrice = round($product->sale_price * $factor, 2);
                $product->update(['sale_price' => max($newPrice, 0)]);
        
                foreach ($product->variants as $variant) {
                    $variantNewPrice = round($variant->sale_price * $factor, 2);
                    $variant->update(['sale_price' => max($variantNewPrice, 0)]);
                }
            }
        });

        $this->reset(['selected_category', 'selected_subcategory', 'adjustment_type', 'percentage']);        

        $this->dispatch('priceChangeApplied'); // Notificación de éxito
    }

    public function render()
    {
        return view('livewire.admin.products.product-prices');
    }
}
