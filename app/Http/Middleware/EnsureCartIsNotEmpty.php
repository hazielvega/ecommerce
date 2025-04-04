<?php

namespace App\Http\Middleware;

use Closure;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCartIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si el carrito está vacío
        if (Cart::instance('shopping')->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'No puedes continuar con el pago porque tu carrito está vacío.');
        }
        
        return $next($request);
    }
}
