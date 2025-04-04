<?php

namespace App\Listeners;

use COM;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreCartItems
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Restaurar el carrito de compras cada vez que el usuario inicie sesión.
        // 'Cart::instance('shopping')': Selecciona la instancia del carrito de compras denominada 'shopping'.
        // 'restore($event->user->id)': Restaura el contenido del carrito de compras
        // para el usuario que se acaba de loguear, utilizando el 'id' del usuario.
        // Esto significa que si el usuario había agregado productos al carrito antes
        // de cerrar sesión, esos productos se restaurarán al volver a iniciar sesión,
        // manteniendo su carrito tal como estaba antes de salir.
        Cart::instance('shopping')->restore($event->user->id);
    }
}
