<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShoppingCart extends Component
{
    public function mount()
    {
        Cart::instance('shopping');
    }

    #[Computed()]
    public function subtotal()
    {
        return Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        })->sum(function ($item) {
            return $item->options['original_price'] * $item->qty ?? $item->price * $item->qty;
        });
    }

    #[Computed()]
    public function hasDiscounts()
    {
        return Cart::content()->contains(function ($item) {
            return isset($item->options['offer']);
        });
    }

    #[Computed()]
    public function discountTotal()
    {
        return Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'] && isset($item->options['offer']);
        })->sum(function ($item) {
            return ($item->options['original_price'] - $item->price) * $item->qty;
        });
    }

    #[Computed()]
    public function totalWithDiscounts()
    {
        return $this->subtotal() - $this->discountTotal();
    }

    #[Computed()]
    public function appliedOffers()
    {
        $offers = [];

        Cart::content()->each(function ($item) use (&$offers) {
            // Verificar que el item tenga oferta, esté en stock y el descuento sea aplicable
            if (
                isset($item->options['offer']) &&
                $item->qty <= $item->options['stock'] &&
                $item->price < $item->options['original_price'] &&
                !array_key_exists($item->options['offer']['offer_id'], $offers)
            ) {

                $offers[$item->options['offer']['offer_id']] = [
                    'offer_id' => $item->options['offer']['offer_id'],
                    'name' => $item->options['offer']['offer_name'],
                    'discount_percent' => $item->options['offer']['discount_percent'],
                    'original_price' => $item->options['original_price'],
                    'final_price' => $item->price
                ];
            }
        });
        return array_values($offers);
    }

    public function increase($rowId)
    {
        Cart::instance('shopping');
        $item = Cart::get($rowId);

        if ($item->qty < $item->options['stock']) {
            Cart::update($rowId, $item->qty + 1);

            if (auth()->check()) {
                Cart::store(auth()->id());
            }

            $this->dispatch('cartUpdated', Cart::count());
        } else {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Stock máximo',
                'text' => 'No puedes agregar más unidades de este producto',
            ]);
        }
    }

    public function decrease($rowId)
    {
        Cart::instance('shopping');
        $item = Cart::get($rowId);

        if ($item->qty > 1) {
            Cart::update($rowId, $item->qty - 1);
        } else {
            Cart::remove($rowId);
        }

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count());
    }

    public function remove($rowId)
    {
        Cart::instance('shopping');
        Cart::remove($rowId);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count());

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'El producto fue removido del carrito',
        ]);
    }

    public function clear()
    {
        Cart::instance('shopping');

        if (Cart::count() > 0) {
            Cart::destroy();

            if (auth()->check()) {
                Cart::store(auth()->id());
            }

            $this->dispatch('cartUpdated', Cart::count());

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Carrito vaciado',
                'text' => 'Todos los productos fueron removidos',
            ]);
        }
    }

    public function validateBeforeCheckout()
    {
        Cart::instance('shopping');
        $validItems = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        if ($validItems->isEmpty()) {
            $message = "No hay productos válidos en tu carrito. ";

            $outOfStockItems = Cart::content()->filter(function ($item) {
                return $item->qty > $item->options['stock'];
            });

            if ($outOfStockItems->isNotEmpty()) {
                $message .= "Algunos productos no tienen suficiente stock disponible.";
            }

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error en el carrito',
                'text' => $message,
            ]);

            return;
        }

        return redirect()->route('shipping.index');
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
