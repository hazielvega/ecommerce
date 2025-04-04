<?php

namespace App\Livewire\Admin\Offers;

use Livewire\Component;
use App\Models\Category;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OfferCreate extends Component
{
    public $categories = [];
    public $selected_products = [];

    public $name;
    public $description;
    public $discount_percentage;
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->categories = Category::with('subcategories.products')->get();
    }

    public function toggleCategory($categoryId)
    {
        $category = $this->categories->where('id', $categoryId)->first();
        if (!$category) return;

        $categoryProducts = $category->subcategories->flatMap->products->pluck('id')->toArray();
        $allSelected = $this->allProductsSelected($categoryProducts);

        foreach ($categoryProducts as $productId) {
            if ($allSelected) {
                unset($this->selected_products[$productId]);
            } else {
                $this->selected_products[$productId] = true;
            }
        }
    }


    public function toggleSubcategory($subcategoryId)
    {
        $subcategory = Subcategory::with('products')->find($subcategoryId);
        if (!$subcategory) return;

        $subcategoryProducts = $subcategory->products->pluck('id')->toArray();
        $allSelected = $this->allProductsSelected($subcategoryProducts);

        foreach ($subcategoryProducts as $productId) {
            if ($allSelected) {
                unset($this->selected_products[$productId]);
            } else {
                $this->selected_products[$productId] = true;
            }
        }
    }


    public function toggleProduct($productId)
    {
        if (isset($this->selected_products[$productId])) {
            unset($this->selected_products[$productId]);
        } else {
            $this->selected_products[$productId] = true;
        }
    }


    private function allProductsSelected($productIds)
    {
        foreach ($productIds as $id) {
            if (!isset($this->selected_products[$id])) {
                return false;
            }
        }
        return true;
    }


    public function createOffer()
    {

        // dd($this->selected_products);
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:1|max:99',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ],[],[
            'name' => 'Nombre',
            'description' => 'Descripción',
            'discount_percentage' => 'Descuento %',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de finalización',
        ]);
    
        DB::transaction(function () {
            // Crear la oferta
            $offer = Offer::create([
                'name' => $this->name,
                'description' => $this->description,
                'discount_percentage' => $this->discount_percentage,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => true,
            ]);
    
            // Obtener solo los IDs de los productos seleccionados
            $productIds = array_keys($this->selected_products);
    
            foreach ($productIds as $productId) {
                OfferProduct::create([
                    'offer_id' => $offer->id,
                    'product_id' => $productId,
                ]);
            }
        });
    
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Oferta creada',
            'text' => 'Oferta creada correctamente',
        ]);
        return redirect()->route('admin.products.index'); // Ajusta la ruta según sea necesario
    }

    public function render()
    {
        return view('livewire.admin.offers.offer-create');
    }
}
