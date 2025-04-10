<?php

namespace App\Livewire\Products;

use App\Models\Feature;
use App\Models\Option;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AddToCart extends Component
{
    public $product;
    public $variant;
    public $quantity = 1;
    public $stock;
    public $selectedFeatures = [];

    public function mount()
    {
        // Cargar la primera variante habilitada
        $firstVariant = $this->product->variants()->where('is_enabled', true)->first();

        if ($firstVariant) {
            $this->variant = $firstVariant;
            $this->stock = $firstVariant->stock;
            $this->selectedFeatures = $firstVariant->features->pluck('id', 'option_id')->toArray();
        }
    }

    public function updatedSelectedFeatures()
    {
        $this->getVariant();
    }

    public function getVariant()
    {
        $this->variant = $this->product->variants()
            ->whereHas('features', function ($query) {
                $query->whereIn('features.id', array_values($this->selectedFeatures));
            })
            ->where('is_enabled', true)
            ->first();

        $this->stock = $this->variant ? $this->variant->stock : 0;
        $this->quantity = $this->variant ? 1 : 0;
    }

    public function add_to_cart()
    {
        if (!$this->variant) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Debe seleccionar una variante válida.',
            ]);
            return;
        }
    
        Cart::instance('shopping');
        $stockDisponible = $this->variant->fresh()->stock;
    
        // Verificar si el producto tiene oferta activa
        $hasOffer = $this->product->offers->isNotEmpty();
        $discountPercent = $hasOffer ? $this->product->offers->first()->discount_percentage : 0;
        $originalPrice = $this->variant->sale_price;
        $finalPrice = $originalPrice * (1 - $discountPercent / 100);
    
        $cartItem = Cart::search(fn($cartItem) => $cartItem->id === $this->variant->id)->first();
    
        if ($cartItem && ($cartItem->qty + $this->quantity > $stockDisponible)) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No hay suficiente stock disponible',
            ]);
            return;
        }
    
        if (!$cartItem && $this->quantity > $stockDisponible) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No hay suficiente stock disponible',
            ]);
            return;
        }
    
        $features = Feature::whereIn('id', $this->selectedFeatures)
            ->pluck('description', 'id')
            ->toArray();
    
        // Datos de la oferta si existe
        $offerData = [];
        if ($hasOffer) {
            $offer = $this->product->offers->first();
            $offerData = [
                'offer_id' => $offer->id,
                'offer_name' => $offer->name,
                'discount_percent' => $offer->discount_percentage,
                'original_price' => $originalPrice,
            ];
        }

    
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->quantity,
            'price' => $finalPrice, // Usamos el precio con descuento si aplica
            'options' => [
                'sku' => $this->variant->sku,
                'image' => Storage::url(json_decode($this->product->image_path, true)[0] ?? 'img/noimage.png'),
                'stock' => $this->variant->stock,
                'features' => $features,
                'offer' => $offerData, // Incluimos datos de la oferta
                'original_price' => $originalPrice, // Guardamos precio original para mostrar comparación
            ]
        ]);
    
        if (auth()->check()) {
            Cart::store(auth()->id());
        }
    
        $this->dispatch('cartUpdated', Cart::count());
    
        $message = 'El producto se agregó al carrito';
        if ($hasOffer) {
            $message .= ' con un '.$discountPercent.'% de descuento';
        }
    
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.products.add-to-cart');
    }
}
