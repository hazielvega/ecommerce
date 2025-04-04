<?php

namespace App\Http\Controllers;

use App\Models\Cover;
use App\Models\Product;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $covers = Cover::where('is_active', true)
            ->whereDate('start_at', '<=', now())   //Debe ser menor o igual que la fecha actual
            ->where(function ($query) {
                $query->whereDate('end_at', '>=', now()) //Debe ser mayor o igual que la fecha actual
                    ->orWhereNull('end_at');             //o el campo end_at es nulo
            })
            ->orderBy('order')                      //Ordenar por orden       
            ->get();
        // Para configurar la zona horaria debo ir a config/app.php y cambiar la zona horaria(timezone)

        // Recupero los ultimos productos
        // $lastProducts = Product::orderBy('created_at', 'DESC')
        //     ->take(12)
        //     ->get();

        // Recupero los ultimos productos que esten activos
        $lastProducts = Product::where('is_enabled', true)
            ->orderBy('created_at', 'DESC')
            ->take(12)
            ->get();


        return view('welcome', compact('covers', 'lastProducts'));
    }
}
