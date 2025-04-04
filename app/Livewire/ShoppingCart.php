<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShoppingCart extends Component
{
    public function mount()
    {
        // Especifico sobre que instancia estoy trabajando
        Cart::instance('shopping');
    }


    // Metoto para obtener el subtotal del carrito
    #[Computed()]
    public function subtotal()
    {
        return Cart::content()->filter(function ($item) {
            // Quiero filtrar los productos que tengan un qty menor que el stock disponible
            return $item->qty <= $item->options['stock'];
        })->sum(function ($item) {
            // Sumo el precio por la cantidad
            return $item->subtotal;
        });
    }


    // Metodo para incrementar la cantidad de un item
    public function increase($rowId)
    {
        Cart::instance('shopping');

        Cart::update($rowId, Cart::get($rowId)->qty + 1);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        // Actualizo la cantidad de items del carrito en el icono
        $this->dispatch('cartUpdated', Cart::count());
    }

    // Metodo para decrementar la cantidad de un item
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

        // Actualizo la cantidad de items del carrito en el icono
        $this->dispatch('cartUpdated', Cart::count());
    }

    // Eliminar un item del carrito
    public function remove($rowId)
    {
        Cart::instance('shopping');
        Cart::remove($rowId);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        // Actualizo la cantidad de items del carrito en el icono
        $this->dispatch('cartUpdated', Cart::count());
    }

    // Metodo para vaciar el carrito
    public function clear()
    {
        Cart::instance('shopping');
        Cart::destroy();

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        // Actualizo la cantidad de items del carrito en el icono        
        $this->dispatch('cartUpdated', Cart::count());
    }

    // Metodo para validar que el carrito no esté vacio antes de continuar
    public function validateBeforeCheckout()
    {
        Cart::instance('shopping');
        $content = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        // dd($content);

        // Verifica si el contenido del carrito es vacio
        if ($content->isEmpty()) {
            $message = "No has seleccionado ningun producto:\n";

            // Emitir evento para mostrar alerta en la vista
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Atención',
                'text' => $message,
            ]);

            return;
        }

        // Si la validación es exitosa, redirigir a la carga de direcciones
        return redirect()->route('shipping.index');
    }


    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
