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
use Illuminate\Support\Facades\Mail;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Configura el access token al inicializar el controlador
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
    }

    public function index()
    {
        Cart::instance('shopping');
        $content = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        // dump($content);

        // Calcular subtotal original (sin descuentos)
        $subtotal = $content->sum(function ($item) {
            // Si existe precio original en las opciones, usarlo
            if (isset($item->options['original_price'])) {
                return $item->options['original_price'] * $item->qty;
            }
            // Si no existe precio original, usar el precio actual (sin descuento)
            return $item->price * $item->qty;
        });
        // dump($subtotal);

        // Calcular descuentos totales
        $discount = $content->sum(function ($item) {
            if (isset($item->options['offer'])) {
                return ($item->options['original_price'] - $item->price) * $item->qty;
            }
            return 0;
        });

        $shipping = 10000;
        $total = ($subtotal - $discount) + $shipping;

        // Obtener direcciones
        $shipping_address = $this->getShippingAddress();
        $billing_address = $this->getBillingAddress();
        $receiver = $this->getReceiver();

        return view('payment.index', compact(
            'content',
            'subtotal',
            'discount',
            'shipping',
            'total',
            'shipping_address',
            'billing_address',
            'receiver'
        ));
    }

    public function createPreference()
    {
        Cart::instance('shopping');
        $content = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        if ($content->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Algunos productos superan el stock disponible');
        }

        $shipping_address = $this->getShippingAddress();
        $billing_address = $this->getBillingAddress();
        $receiver = $this->getReceiver();

        if (!$shipping_address || !$billing_address || !$receiver) {
            return redirect()->route('checkout')->with('error', 'Completa tus datos de envío y facturación');
        }

        $items = $content->map(function ($item) {
            $itemData = [
                'title' => $item->name,
                'quantity' => (int) $item->qty, // Asegurar que sea entero
                'unit_price' => (float) $item->price, // Asegurar que sea float
                'currency_id' => 'ARS',
            ];

            return $itemData;
        })->values()->toArray(); // ¡Clave! ->values() resetea los índices a numéricos

        // dump($items);

        $preferenceData = [
            'items' => $items,
            'payer' => [
                'email' => "test_user_123456@testuser.com",
            ],
            'auto_return' => 'approved',
            'back_urls' => [
                'success' => route('payment.success'),
                'failure' => route('payment.failure'),
            ],
            // 'external_reference' => uniqid(),
        ];

        // dump($preferenceData);
        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            $order = $this->createOrder(
                $preference->id,
                $content,
                $shipping_address,
                $billing_address,
                $receiver
            );
            return redirect()->away($preference->init_point);
        } catch (MPApiException $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    protected function createOrder($preferenceId, $cartItems, $shipping_address, $billing_address, $receiver)
    {
        Cart::instance('shopping');
        $content = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        // dump($content);

        // Calcular subtotal original (sin descuentos)
        $subtotal = $content->sum(function ($item) {
            // Si existe precio original en las opciones, usarlo
            if (isset($item->options['original_price'])) {
                return $item->options['original_price'] * $item->qty;
            }
            // Si no existe precio original, usar el precio actual (sin descuento)
            return $item->price * $item->qty;
        });
        // dump($subtotal);

        // Calcular descuentos totales
        $discount = $content->sum(function ($item) {
            if (!empty($item->options['offer'])) {
                return ($item->options['original_price'] - $item->price) * $item->qty;
            }
            return 0;
        });

        $shipping = 10000;
        $total = ($subtotal - $discount) + $shipping;

        $order = Order::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'receiver_id' => $receiver->id,
            'shipping_address_id' => $shipping_address->id,
            'billing_address_id' => $billing_address->id,
            'payment_id' => $preferenceId,
            'status' => OrderStatus::Pending->value, // Usamos el valor numérico del enum
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
        ]);

        foreach ($cartItems as $item) {
            // Recupero la variante
            $variant = Variant::where('id', $item->id)->first();
            $orderItemData = [
                'order_id' => $order->id,
                'variant_id' => $variant->id,
                'quantity' => $item->qty,
                'price' => $item->price,
                'original_price' => $$variant->sale_price,
                'subtotal' => $item->price * $item->qty,
            ];
            OrderItem::create($orderItemData);
        }

        return $order;
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

    public function success(Request $request)
    {
        // Obtener el ID de preferencia de la URL
        $preferenceId = $request->query('preference_id');

        // Buscar la orden pendiente
        $order = Order::where('payment_id', $preferenceId)->firstOrFail();

        // Actualizar estado a "approved" (asumimos que el pago fue exitoso)
        $order->update(['status' => OrderStatus::Pending->value]);

        // Generar PDF y enviar email
        $this->generateOrderDocuments($order);

        // Vaciar carrito y redirigir a gracias
        Cart::instance('shopping')->destroy();
        return view('gracias', compact('order'));
    }

    public function failure(Request $request)
    {
        // Opcional: Marcar la orden como fallida si existe
        if ($preferenceId = $request->query('preference_id')) {
            Order::where('payment_id', $preferenceId)->update(['status' => OrderStatus::Failed->value]);
        }

        return view('payment.failure');
    }

    protected function generateOrderDocuments($order)
    {
        $pdf = Pdf::loadView('orders.ticket', [
            'order' => $order,
            'shipping_address' => $order->shippingAddress,
            'billing_address' => $order->billingAddress,
            'receiver' => $order->receiver,
        ])->setPaper('a4');

        $pdfPath = 'tickets/ticket-' . $order->id . '.pdf';
        $pdf->save(storage_path('app/public/' . $pdfPath));
        $order->update(['pdf_path' => $pdfPath]);

        // Enviar email
        $email = auth()->check() ? auth()->user()->email : $order->receiver->email;
        Mail::to($email)->send(new OrderCreatedMail($order));
    }
}
