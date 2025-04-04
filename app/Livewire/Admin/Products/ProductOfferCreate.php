<?php

namespace App\Livewire\Admin\Products;

use App\Models\Offer;
use App\Models\OfferProduct;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductOfferCreate extends Component
{
    public $product;
    public $offers;

    // Modal para ofertas
    public $showOfferModal = false;

    public $name;
    public $description;
    public $discount_percentage;
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->offers = Offer::whereHas('products', function ($query) {
            $query->where('product_id', $this->product->id);
        })->get();
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
        ], [], [
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

            OfferProduct::create([
                'offer_id' => $offer->id,
                'product_id' => $this->product->id,
            ]);
        });

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Oferta creada',
            'text' => 'Oferta creada correctamente',
        ]);

        // Redirijo a la pantalla de edicion del producto
        return redirect()->route('admin.products.edit', $this->product);
    }


    public function render()
    {
        return view('livewire.admin.products.product-offer-create');
    }
}
