<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\EnsureCartIsNotEmpty;
use App\Http\Middleware\VerifyStock;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Receiver;
use App\Models\Variant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;


// Ruta para la vista de bienvenida
Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');


// Ruta para mostrar los productos de una determinada categoria
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Ruta para mostrar los productos que estan en oferta
Route::get('/offers', [OfferController::class, 'show'])->name('offers.show');

// Ruta para mostrar los productos de una determinada subcategoria
Route::get('/subcategories/{subcategory}', [SubcategoryController::class, 'show'])->name('subcategories.show');

// Ruta para mostrar los productos que cumplan con una busqueda
Route::get('/products/search/{search}', [ProductController::class, 'search'])->name('products.search');

// Ruta para mostrar el detalle de un producto
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Ruta para mostrar el carrito de compras
Route::get('cart', [CartController::class, 'index'])->name('cart.index');

// Ruta para controllar los envíos
Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index')->middleware(EnsureCartIsNotEmpty::class);

// Ruta para ver lista de pedidos
Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
// Ruta para ver detalle de un pedido
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
// Ruta para descargar el ticket de un pedido
Route::get('/orders/{order}/download-ticket', [OrderController::class, 'downloadTicket'])->name('orders.download');


// MercadoPago
Route::get('/checkoutMP', [PaymentController::class, 'createPreference'])->name('payment.checkout');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');


// Rutas para realizar una compra
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware(EnsureCartIsNotEmpty::class);
// Ruta para capturar el pago
// al ser una ruta de tipo post, necesito pasar el token csrf. La redireccion va a estar a cargo de Niubiz,
// por lo que no va a ser posible pasarle el token. Debo realizar una excepción en bootstrap\app.php
Route::post('checkout/paid', [CheckoutController::class, 'paid'])->name('checkout.paid');

Route::get('gracias', function () {
    return view('gracias');
})->name('gracias');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('prueba', function () {
    $order = Order::find(2);
    //Recupero la direccion de envio de la orden creada
    $shipping_address = Address::find($order->shipping_address_id);
    //Recupero la direccion de facturacion de la orden creada
    $billing_address = Address::find($order->billing_address_id);
    // Recupero la informacion del destinatario
    $receiver = Receiver::find($order->receiver_id);

    $pdf = Pdf::loadView('orders.ticket', compact('shipping_address', 'billing_address', 'receiver', 'order'))->setPaper('a4');
    dump($order->items);
});
