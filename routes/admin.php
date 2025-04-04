<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CoverController;
use App\Http\Controllers\admin\OfferController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReportController;
use App\Livewire\Admin\Users\UserComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->middleware('can:access dashboard')->name('dashboard');

Route::get('/options', [OptionController::class, 'index'])->middleware('can:manage options')->name('options.index');


Route::resource('categories', CategoryController::class)->middleware('can:manage categories');

Route::resource('subcategories', SubcategoryController::class)->middleware('can:manage subcategories');

Route::resource('products', ProductController::class)->middleware('can:manage products');

// Ruta para los reportes de productos en excel
Route::get('products-export', [ProductController::class, 'export'])->name('products.export');

// Ruta para editar una variante
Route::get('/products/{product}/variants/{variant}', [ProductController::class, 'variants'])
    ->name('products.variants')
    ->scopeBindings(); //Este metodo verifica la relacion entre el producto y la variante. Si existe me permite ingresar.


// Ruta para actualizar una variante
Route::put('/products/{product}/variants/{variant}', [ProductController::class, 'variantsUpdate'])
    ->name('products.variantsUpdate')
    ->scopeBindings(); //Este metodo verifica la relacion entre el producto y la variante. Si existe me permite ingresar.


// Rutas para el crud de los covers/portadas
Route::resource('covers', CoverController::class)->middleware('can:manage covers');

// Ruta para el crud de ordenes
Route::get('orders', [OrderController::class, 'index'])->middleware('can:manage orders')->name('orders.index');

// Ruta para crud de usuarios
Route::get('users', [UserController::class, 'index'])->middleware('can:manage users')->name('users.index');

// Ruta para los reportes estadisticos
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Rutas para las ofertas
Route::resource('offers', OfferController::class);


