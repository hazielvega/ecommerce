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
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
    }

    public function index()
    {
        Cart::instance('shopping');
        $content = Cart::content()->filter(function ($item) {
            return $item->qty <= $item->options['stock'];
        });

        $subtotal = $content->sum(function ($item) {
            return ($item->options['original_price'] ?? $item->price) * $item->qty;
        });

        $discount = $content->sum(function ($item) {
            if (isset($item->options['offer'])) {
                return ($item->options['original_price'] - $item->price) * $item->qty;
            }
            return 0;
        });

        $shipping = 10000;
        $total = ($subtotal - $discount) + $shipping;

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

        // Crear la orden con estado Pending
        $order = Order::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'receiver_id' => $receiver->id,
            'shipping_address_id' => $shipping_address->id,
            'billing_address_id' => $billing_address->id,
            'payment_id' => null, // Temporal, se actualizará después
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

        // Preparar items para MercadoPago
        $items = $validItems->map(function ($item) {
            return [
                'title' => $item->name,
                'quantity' => (int) $item->qty,
                'unit_price' => (float) $item->price,
                'currency_id' => 'ARS',
            ];
        })->values()->toArray();

        $preferenceData = [
            'items' => $items,
            'payer' => [
                'name' => $receiver->name,
                'surname' => $receiver->last_name,
                'email' => auth()->user()->email, // Usar email real del usuario
                'phone' => [
                    'number' => $receiver->phone
                ],
                'address' => [
                    'street_name' => $shipping_address->calle,
                    'street_number' => $shipping_address->numero,
                    'zip_code' => $shipping_address->codigo_postal
                ]
            ],
            'auto_return' => 'approved',
            'back_urls' => [
                'success' => route('payment.success'),
                'failure' => route('payment.failure'),
            ],
            'external_reference' => $order->id,
            'notification_url' => route('payment.webhook'), // Para recibir notificaciones IPN
        ];

        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            // Actualizar la orden con el ID de la preferencia
            $order->update([
                'payment_id' => $preference->id,
                'card_number' => request('card_number') // Si estás capturando esta info
            ]);

            return redirect()->away($preference->init_point);
        } catch (MPApiException $e) {
            // Si hay error, cambiar estado a Cancelled
            $order->update(['status' => OrderStatus::Cancelado->value]);

            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->input('external_reference');
        $preferenceId = $request->input('preference_id');

        if (!$orderId) {
            return redirect()->route('cart.show')->with('error', 'No se pudo identificar la orden');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('cart.show')->with('error', 'Orden no encontrada');
        }

        // Actualizar estado de la orden a Processing
        $order->update([
            'status' => OrderStatus::Procesando->value,
            'payment_id' => $preferenceId ?? $order->payment_id,
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

        // Generar documentos y enviar email
        $this->generateOrderDocuments($order);

        return view('gracias', compact('order'));
    }

    public function failure(Request $request)
    {
        $orderId = $request->input('external_reference');

        if ($orderId) {
            Order::where('id', $orderId)
                ->update(['status' => OrderStatus::Cancelado->value]);
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
