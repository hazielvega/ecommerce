<?php

namespace App\Observers;

use App\Mail\OrderCreatedMail;
use App\Models\Address;
use App\Models\Order;
use App\Models\Receiver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use LaravelLang\Publisher\Console\Add;

class OrderObserver
{
    public function created(Order $order)
    {
        // $order->load([
        //     'items.variant.product', 
        //     'items.variant.features'
        // ]);

        // //Recupero la direccion de envio de la orden creada
        // $shipping_address = Address::find($order->shipping_address_id);
        // //Recupero la direccion de facturacion de la orden creada
        // $billing_address = Address::find($order->billing_address_id);
        // // Recupero la informacion del destinatario
        // $receiver = Receiver::find($order->receiver_id);
        
        // $pdf = Pdf::loadView('orders.ticket', compact('shipping_address', 'billing_address', 'receiver', 'order'))->setPaper('a4');

        // $pdf->save(storage_path('app/public/tickets/ticket-' . $order->id . '.pdf'));

        // $order->pdf_path = 'tickets/ticket-' . $order->id . '.pdf';
        // $order->save();

        // Envía el PDF por correo al usuario autenticado o al correo del destinatario si no está registrado
        // if (auth()->check()) {
        //     Mail::to(auth()->user()->email)->send(new OrderCreatedMail($order));
        // } else {
        //     Mail::to($receiver->email)->send(new OrderCreatedMail($order));
        // }
    }
}
