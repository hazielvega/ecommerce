<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Recupero todos los pedidos del usuario autenticado
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    // Metodo para descargar el ticket
    public function downloadTicket(Order $order)
    {
        return Storage::download($order->pdf_path);
    }
}
