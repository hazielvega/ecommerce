<?php

namespace App\Livewire\Admin\Offers;

use Livewire\Component;
use App\Models\Offer;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\OfferProduct;
use Illuminate\Support\Facades\DB;

class OfferEdit extends Component
{
    public $offer;
    public $name;
    public $description;
    public $discount_percentage;
    public $start_date;
    public $end_date;
    public $selected_products = [];
    public $categories = [];

    public function mount($offer)
    {
        // $this->offer = Offer::with('products')->findOrFail($offer);

        // Cargar datos de la oferta
        $this->name = $this->offer->name;
        $this->description = $this->offer->description;
        $this->discount_percentage = $this->offer->discount_percentage;
        $this->start_date = $this->offer->start_date;
        $this->end_date = $this->offer->end_date;

        // Obtener productos ya asignados a la oferta
        $this->selected_products = $this->offer->products->pluck('id')->mapWithKeys(fn ($id) => [$id => true])->toArray();

        // Cargar categorías con subcategorías y productos
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

    public function updateOffer()
    {
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
            // Actualizar la oferta
            $this->offer->update([
                'name' => $this->name,
                'description' => $this->description,
                'discount_percentage' => $this->discount_percentage,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => true,
            ]);

            // Obtener solo los IDs de los productos seleccionados
            $productIds = array_keys($this->selected_products);

            // Eliminar productos que ya no están en la oferta
            OfferProduct::where('offer_id', $this->offer->id)
                ->whereNotIn('product_id', $productIds)
                ->delete();

            // Agregar o mantener productos en la oferta
            foreach ($productIds as $productId) {
                // Verificar si el producto ya está en una oferta con mayor descuento
                $existingOffer = OfferProduct::where('product_id', $productId)
                    ->whereHas('offer', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->with('offer')
                    ->first();

                if (!$existingOffer || $existingOffer->offer->discount_percentage < $this->discount_percentage) {
                    OfferProduct::updateOrCreate(
                        ['offer_id' => $this->offer->id, 'product_id' => $productId]
                    );
                }
            }
        });

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizada',
            'text' => 'Oferta actualizada correctamente',
        ]);
        return redirect()->route('admin.offers.edit', $this->offer);
    }

    public function render()
    {
        return view('livewire.admin.offers.offer-edit');
    }
}

