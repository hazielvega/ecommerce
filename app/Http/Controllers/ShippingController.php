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
        Cart::instance('shopping');

        // Filtramos los productos con stock válido
        $content = Cart::content()->filter(fn($item) => $item->qty <= $item->options['stock']);

        // Calculamos subtotal y descuentos
        $subtotal = $content->sum(fn($item) => $item->price * $item->qty);

        // Calculamos el total de descuentos aplicados
        $discount = $content->sum(function ($item) {
            if (isset($item->options['offer'])) {
                return ($item->options['original_price'] - $item->price) * $item->qty;
            }
            return 0;
        });

        $shipping = 10000; // Precio fijo de envío

        // Total con descuentos aplicados
        $total = ($subtotal - $discount) + $shipping;

        // Direcciones y destinatarios
        $addresses = auth()->check()
            ? Address::where('user_id', auth()->id())->get()
            : Address::whereNull('user_id')->where('session_id', session()->getId())->get();

        $receivers = auth()->check()
            ? Receiver::where('user_id', auth()->id())->get()
            : Receiver::whereNull('user_id')->where('session_id', session()->getId())->get();

        return view('shipping.index', compact(
            'content',
            'subtotal',
            'shipping',
            'total',
            'addresses',
            'receivers',
            'discount'
        ));
    }
}
