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
use Gloudemans\Shoppingcart\Facades\Cart;
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

// Ruta para controlar los envíos
Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index')->middleware(EnsureCartIsNotEmpty::class);

// Ruta para ver lista de pedidos
Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
// Ruta para ver detalle de un pedido
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
// Ruta para descargar el ticket de un pedido
Route::get('/orders/{order}/download-ticket', [OrderController::class, 'downloadTicket'])->name('orders.download');


// MercadoPago
Route::get('/checkout', [PaymentController::class, 'index'])->name('checkout.index');
Route::post('/checkout/payment', [PaymentController::class, 'createPreference'])->name('payment.create');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');


// Niubiz
// Rutas para realizar una compra
// Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware(EnsureCartIsNotEmpty::class);
// // Ruta para capturar el pago
// Route::post('checkout/paid', [CheckoutController::class, 'paid'])->name('checkout.paid');

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
    return view('payment.failure');
});

Route::get('/test-listener', function() {
    $order = \App\Models\Order::first(); // Obtén una orden existente
    $oldStatus = $order->status;
    // $order->status = \App\Enums\OrderStatus::Shipped; // Cambia a un estado diferente

    dd($oldStatus);
    
    // event(new \App\Events\OrderStatusUpdated($order, $oldStatus));
    
    // return 'Evento disparado';
});