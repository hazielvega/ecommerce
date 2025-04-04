<?php

use App\Http\Controllers\Api\SortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Ruta para ordenar los covers 
Route::post('/sort/covers', [SortController::class, 'covers'])->name('api.sort.covers');
