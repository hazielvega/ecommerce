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

        Cart::add([
            'id' => $this->variant->id,
            'name' => $this->product->name,
            'qty' => $this->quantity,
            'price' => $this->variant->sale_price,
            'options' => [
                'sku' => $this->variant->sku,
                'image' => Storage::url(json_decode($this->product->image_path, true)[0] ?? 'img/noimage.png'), // Solo la primera imagen
                'stock' => $this->variant->stock,
                'features' => $features
            ]
        ]);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count());

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'El producto se agregó al carrito'
        ]);
    }

    public function render()
    {
        return view('livewire.products.add-to-cart');
    }
}
