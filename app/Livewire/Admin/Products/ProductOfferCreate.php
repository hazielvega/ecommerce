<?php

namespace App\Livewire\Admin\Products;

use App\Models\Offer;
use App\Models\OfferProduct;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductOfferCreate extends Component
{
    public $product;
    public $offers;
    public $activeOffer;

    // Modal control
    public $showOfferModal = false;

    // Offer form fields
    public $name;
    public $description;
    public $discount_percentage;
    public $start_date;
    public $end_date;

    public function mount($product)
    {
        $this->product = $product;
        $this->loadOffers();
        $this->activeOffer = $this->product->activeOffer();
    }

    public function loadOffers()
    {
        $this->offers = Offer::query()
            ->whereHas('products', fn($query) => $query->where('product_id', $this->product->id))
            ->withCount('products')
            ->latest()
            ->get();
    }

    public function createOffer()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:offers,name',
            'description' => 'nullable|string|max:500',
            'discount_percentage' => 'required|numeric|min:1|max:99',
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before:end_date'
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->diffInDays($this->start_date) > 365) {
                        $fail('La oferta no puede durar más de 1 año');
                    }
                }
            ],
        ], [], [
            'name' => 'nombre',
            'description' => 'descripción',
            'discount_percentage' => 'porcentaje de descuento',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de finalización',
        ]);

        try {
            DB::transaction(function () {
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

                // Desactivar otras ofertas para este producto
                OfferProduct::where('product_id', $this->product->id)
                    ->where('offer_id', '!=', $offer->id)
                    ->update(['is_active' => false]);
            });

            $this->loadOffers();
            $this->activeOffer = $this->product->fresh()->activeOffer();
            $this->resetOfferForm();
            $this->showOfferModal = false;

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Oferta creada',
                'message' => 'La oferta se ha creado correctamente y está activa para este producto.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error al crear oferta',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function activateOffer($offerId)
    {
        try {
            DB::transaction(function () use ($offerId) {
                // Desactivar todas las ofertas para este producto
                OfferProduct::where('product_id', $this->product->id)
                    ->update(['is_active' => false]);

                // Activar la oferta seleccionada
                OfferProduct::where('product_id', $this->product->id)
                    ->where('offer_id', $offerId)
                    ->update(['is_active' => true]);
            });

            $this->loadOffers();
            $this->activeOffer = $this->product->fresh()->activeOffer();

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Oferta activada',
                'message' => 'La oferta se ha activado correctamente para este producto.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error al activar oferta',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deactivateOffer($offerId)
    {
        try {
            OfferProduct::where('product_id', $this->product->id)
                ->where('offer_id', $offerId)
                ->update(['is_active' => false]);

            $this->loadOffers();
            $this->activeOffer = null;

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Oferta desactivada',
                'message' => 'La oferta se ha desactivado correctamente.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error al desactivar oferta',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function resetOfferForm()
    {
        $this->reset([
            'name',
            'description',
            'discount_percentage',
            'start_date',
            'end_date'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.products.product-offer-create');
    }
}
