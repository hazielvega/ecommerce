<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Receiver;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        // Usamos la instancia 'shopping' del carrito de compras
        Cart::instance('shopping');

        // Filtramos los productos para asegurarnos de que no superen el stock disponible
        $content = Cart::content()->filter(fn($item) => $item->qty <= $item->options['stock']);

        // Calculamos el subtotal sumando los subtotales de los productos en el carrito
        $subtotal = $content->sum(fn($item) => $item->subtotal);

        // Definimos un precio fijo de envío (esto puede cambiar en el futuro)
        $shipping = 10000;

        // Calculamos el total a pagar
        $total = $subtotal + $shipping;

        // Recuperamos las direcciones dependiendo de si el usuario está autenticado o no
        $addresses = auth()->check()
            ? Address::where('user_id', auth()->id())->get()
            : Address::whereNull('user_id')->where('session_id', session()->getId())->get();

        // Recupero los destinatarios dependiendo de si el usuario esta autenticado o no
        $receivers = auth()->check()
            ? Receiver::where('user_id', auth()->id())->get()
            : Receiver::whereNull('user_id')->where('session_id', session()->getId())->get();

        return view('shipping.index', compact('content', 'subtotal', 'shipping', 'total', 'addresses', 'receivers'));
    }
}

