<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
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
            return ($item->options['original_price'] ?? $item->price) * $item->qty;
        });

        $discount = $content->sum(function ($item) {
            if (isset($item->options['offer'])) {
                return ($item->options['original_price'] - $item->price) * $item->qty;
            }
            return 0;
        });

        // Defino el precio de envio
        $shipping = 10000;

        // Calculo el total
        $total = ($subtotal - $discount) + $shipping;

        $access_token = $this->generateAccessToken();

        $session_token = $this->generateSessionToken($access_token, $total);

        $shipping_address = $this->getShippingAddress();
        $billing_address = $this->getBillingAddress();
        $receiver = $this->getReceiver();

        return view('checkout.index', compact(
            'content',
            'subtotal',
            'discount',
            'shipping',
            'total',
            'session_token',
            'shipping_address',
            'billing_address',
            'receiver'
        ));
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
            $hasInvalidItems = false;
            $outOfStockProducts = [];
            $validItems = collect();

            // Verificar cada item en el carrito con stock actualizado
            foreach (Cart::content() as $item) {
                // Obtener la variante actual desde la base de datos
                $variant = Variant::find($item->options['variant_id']);
                $currentStock = $variant ? $variant->stock : 0;

                // Verificar si el item es válido
                if ($item->qty > $currentStock) {
                    $hasInvalidItems = true;
                    $outOfStockProducts[] = $item->name;
                    continue;
                }

                // Si el item es válido, agregarlo a la colección
                $validItems->push($item);
            }

            // Si hay items inválidos
            if ($hasInvalidItems) {
                $message = "Algunos productos no tienen suficiente stock disponible:";
                $message .= "<ul class='list-disc pl-5 mt-2'>";
                foreach ($outOfStockProducts as $productName) {
                    $message .= "<li>{$productName}</li>";
                }
                $message .= "</ul>";

                return redirect()->route('cart.index')->with([
                    'error' => 'Productos sin stock suficiente',
                    'error_details' => $message
                ]);
            }

            // Si el carrito está vacío
            if ($validItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'No hay productos válidos en tu carrito');
            }

            // Verificar datos de envío y facturación
            $shipping_address = $this->getShippingAddress();
            $billing_address = $this->getBillingAddress();
            $receiver = $this->getReceiver();
            $shipping_price = 10000;

            if (!$shipping_address || !$billing_address || !$receiver) {
                return redirect()->route('checkout')->with('error', 'Completa tus datos de envío y facturación');
            }

            // Calcular totales con items válidos
            $subtotal = $validItems->sum(function ($item) {
                return ($item->options['original_price'] ?? $item->price) * $item->qty;
            });

            $discount = $validItems->sum(function ($item) {
                if (!empty($item->options['offer'])) {
                    return ($item->options['original_price'] - $item->price) * $item->qty;
                }
                return 0;
            });

            $total = ($subtotal - $discount) + $shipping_price;

            // Creo una nueva orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'receiver_id' => $receiver->id,
                'shipping_address_id' => $shipping_address->id,
                'billing_address_id' => $billing_address->id,
                'billing_document' => $receiver->document_number,
                'payment_id' => $response['dataMap']['TRANSACTION_ID'],
                // 'card_number' => $response['dataMap']['CARD'],
                'total' => $total,
                'status' => OrderStatus::Pendiente->value,
                'shipping_cost' => $shipping_price,
            ]);

            // Crear items de la orden solo con productos válidos
            foreach ($validItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $item->options['variant_id'],
                    'offer_id' => $item->options['offer']['offer_id'] ?? null,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'original_price' => $item->options['offer']['original_price'] ?? $item->price,
                    'discount_percentage' => $item->options['offer']['discount_percent'] ?? 0,
                    'subtotal' => $item->price * $item->qty,
                ]);
            }

            // Genero el PDF
            $this->generateOrderDocuments($order);

            // Actualizar estado de la orden a Processing
            $order->update([
                'status' => OrderStatus::Procesando->value,
                // 'payment_id' => $preferenceId ?? $order->payment_id,
            ]);

            // Actualizar stock de las variantes
            foreach ($order->items as $item) {
                $variant = Variant::find($item->variant_id);
                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                }
            }

            // Limpiar carrito
            Cart::instance('shopping')->destroy();


            return redirect()->route('gracias', compact('order'));
        }
        // Sino, redireccionar a la vista de checkout
        return redirect()->route('checkout.index');
    }

    protected function generateOrderDocuments($order)
    {
        $pdf = Pdf::loadView('orders.ticket', [
            'order' => $order,
            'shipping_address' => $order->shippingAddress,
            'billing_address' => $order->billingAddress,
            'receiver' => $order->receiver,
            'shipping_cost' => $order->shipping_cost,
        ])->setPaper('a4');

        $pdfPath = 'tickets/ticket-' . $order->id . '.pdf';
        $pdf->save(storage_path('app/public/' . $pdfPath));
        $order->update(['pdf_path' => $pdfPath]);

        // Enviar email
        $email = auth()->check() ? auth()->user()->email : $order->receiver->email;
        Mail::to($email)->send(new OrderCreatedMail($order));
    }

    protected function getShippingAddress()
    {
        return auth()->check()
            ? Address::where('user_id', auth()->id())->where('is_shipping', true)->first()
            : Address::where('session_id', session()->getId())->where('is_shipping', true)->first();
    }

    protected function getBillingAddress()
    {
        return auth()->check()
            ? Address::where('user_id', auth()->id())->where('is_billing', true)->first()
            : Address::where('session_id', session()->getId())->where('is_billing', true)->first();
    }

    protected function getReceiver()
    {
        return auth()->check()
            ? Receiver::where('user_id', auth()->id())->where('default', true)->first()
            : Receiver::where('session_id', session()->getId())->where('default', true)->first();
    }
}
