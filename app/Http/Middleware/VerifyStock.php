<?php

namespace App\Http\Middleware;

use App\Models\Variant;
use Closure;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyStock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    //  Middleware para verificar stock. Se va a ejecutar cada vez que quiera entrar a lista para ver el carrito de compras.
    public function handle(Request $request, Closure $next): Response
    {
        // // Configura la instancia del carrito de compras para usar 'shopping' como instancia específica.
        Cart::instance('shopping');

        // Limpia el carrito
        // Cart::destroy();

        // Recorre el carrito de compras
        foreach (Cart::content() as $cartItem) {
            // dd($cartItem->id);
            // Recupero la variante
            // $variant = Variant::where('id', $cartItem->id)->first();
            // dd($variant->stock);

            // Recupero todas las opciones de la variante para la actualizacion
            // $options = $cartItem->options;
            // Recupero la variante
            // $variant = Variant::where('sku', $options->variant->sku)->first();
            // Modifico el campo stock del array de opciones


            // $options['stock'] = $variant->stock;

            // // Actualizo el producto en el  carrito
            // Cart::update($cartItem->rowId, [
            //     'options' => $options,
            // ]);
        }
        return $next($request);
    }
}
