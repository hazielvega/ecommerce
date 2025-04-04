<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreatedMail;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Receiver;
use App\Models\Variant;
use Barryvdh\DomPDF\Facade\Pdf;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class CheckoutController extends Controller
{

    public function index()
    {
        Cart::instance('shopping');
        // Filtro los items que no supere el stock

        $content = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        // Calculo subtotal
        $subtotal = $content->sum(function ($item) {
            return $item->subtotal;
        });

        // Defino el precio de envio
        $shipping = 10000;

        // Calculo el total
        $total = $subtotal + $shipping;

        $access_token = $this->generateAccessToken();

        $session_token = $this->generateSessionToken($access_token, $total);

        // Direccion de envio
        // Recupero la direccion de envio por defecto
        if (!auth()->check()) {
            $shipping_address = Address::where('session_id', session()->getId())
                ->where('is_shipping', true)->first();
        } else {
            $shipping_address = Address::where('user_id', auth()->id())
                ->where('is_shipping', true)->first();
        }

        // Direccion de facturacion
        // Recupero la direccion de facturacion por defecto
        if (!auth()->check()) {
            $billing_address = Address::where('session_id', session()->getId())
                ->where('is_billing', true)->first();
        } else {
            $billing_address = Address::where('user_id', auth()->id())
                ->where('is_billing', true)->first();
        }

        // Destinatario
        // Recupero el destinatario por defecto
        if (!auth()->check()) {
            $receiver = Receiver::where('session_id', session()->getId())->getId()
                ->where('default', true)->first();
        } else {
            $receiver = Receiver::where('user_id', auth()->id())
                ->where('default', true)->first();
        }

        return view('checkout.index', compact('content', 'subtotal', 'shipping', 'total', 'session_token', 'shipping_address', 'billing_address', 'receiver'));
    }

    public function generateAccessToken()
    {
        $url_api = config('services.niubiz.url_api') . '/api.security/v1/security';

        $user = config('services.niubiz.user');

        $password = config('services.niubiz.password');

        $auth = base64_encode($user . ':' . $password);

        return Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
        ])->get($url_api)->body();
    }

    public function generateSessionToken($access_token, $total)
    {
        $merchant_id = config('services.niubiz.merchant_id');
        $url_api = config('services.niubiz.url_api') . "/api.ecommerce/v2/ecommerce/token/session/{$merchant_id}";

        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'Content-Type' => 'application/json',
        ])
            ->post($url_api, [
                'channel' => 'web',
                'amount' => $total,
                'antifraud' => [
                    'client_ip' => '127.0.0.1',
                    'merchantDefineData' => [
                        'MDD15' => 'value15',
                        'MDD20' => 'value20',
                        'MDD33' => 'value33',
                    ]
                ]
            ])
            ->json();

        return $response['sessionKey'];
    }

    // Metodo para capturar el pago
    public function paid(Request $request)
    {
        $access_token = $this->generateAccessToken();
        $merchant_id = config('services.niubiz.merchant_id');
        $url_api = config('services.niubiz.url_api') . "/api.authorization/v3/authorization/ecommerce/{$merchant_id}";

        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'Content-Type' => 'application/json',
        ])->post($url_api, [
            "channel" => "web",
            "captureType" => "manual",
            "countable" => true,
            "order" => [
                "tokenId" => $request->transactionToken,
                "purchaseNumber" => $request->purchaseNumber,
                "amount" => $request->amount,
                "currency" => "USD",
            ],
        ])->json();

        // Mostrar la informacion de la transaccion
        session()->flash('niubiz', [
            'response' => $response,
            'purchaseNumber' => $request->purchaseNumber,
        ]);

        // dd($response);

        // Si la transacción fue exitosa, redireccionar a la vista de gracias
        if (isset($response['dataMap']) && $response['dataMap']['ACTION_CODE'] == '000') {
            Cart::instance('shopping');
            // Filtro los items que no supere el stock
            $content = Cart::content()->filter(function ($item) {
                return $item->qty <= $item->options['stock'];
            });
            // Recupero la direccion de envio por defecto
            if (!auth()->check()) {
                $shipping_address = Address::where('session_id', session()->getId())
                    ->where('is_shipping', true)->first();
            } else {
                $shipping_address = Address::where('user_id', auth()->id())
                    ->where('is_shipping', true)->first();
            }

            // Recupero la direccion de facturacion por defecto
            if (!auth()->check()) {
                $billing_address = Address::where('session_id', session()->getId())
                    ->where('is_billing', true)->first();
            } else {
                $billing_address = Address::where('user_id', auth()->id())
                    ->where('is_billing', true)->first();
            }

            // Recupero el destinatario por defecto
            if (!auth()->check()) {
                $receiver = Receiver::where('session_id', session()->getId())->getId()
                    ->where('default', true)->first();
            } else {
                $receiver = Receiver::where('user_id', auth()->id())
                    ->where('default', true)->first();
            }


            // Creo una nueva orden
            $order = Order::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'session_id' => session()->getId(),
                'receiver_id' => $receiver->id,
                'shipping_address_id' => $shipping_address->id,
                'billing_address_id' => $billing_address->id,
                'payment_id' => $response['dataMap']['TRANSACTION_ID'],
                'card_number' => $response['dataMap']['CARD'],
                'total' => $response['dataMap']['AMOUNT'],
            ]);
            // Recorro el carrito para crear los order_items
            foreach (Cart::content() as $cartItem) {
                // Recupero la variante
                $variant = Variant::where('id', $cartItem->id)->first();

                // Creo el order_item
                OrderItem::create([
                    'order_id' => Order::latest()->first()->id,
                    'variant_id' => $variant->id,
                    'quantity' => $cartItem->qty,
                    'price' => $variant->sale_price,
                    'subtotal' => $variant->sale_price * $cartItem->qty
                ]);
            }

            // Genero el PDF
            $pdf = Pdf::loadView('orders.ticket', compact('shipping_address', 'billing_address', 'receiver', 'order'))->setPaper('a4');
            $pdf->save(storage_path('app/public/tickets/ticket-' . $order->id . '.pdf'));
            $order->pdf_path = 'tickets/ticket-' . $order->id . '.pdf';
            $order->save();

            // Envía el PDF por correo al usuario autenticado o al correo del destinatario si no está registrado
            if (auth()->check()) {
                Mail::to(auth()->user()->email)->send(new OrderCreatedMail($order));
            } else {
                Mail::to($receiver->email)->send(new OrderCreatedMail($order));
            }

            // Actualizo el stock de las variantes
            foreach ($content as $item) {
                $variant = Variant::where('sku', $item->options['sku'])
                    ->decrement('stock', $item->qty);
                // Elimino el item del carrito
                Cart::remove($item->rowId);
            }


            return redirect()->route('gracias');
        }
        // Sino, redireccionar a la vista de checkout
        return redirect()->route('checkout.index');
    }
}
