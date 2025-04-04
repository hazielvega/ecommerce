<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function createPreference()
    {
        // Datos de prueba SIMPLES (producto ficticio)
        $items = [
            [
                "title" => "Producto de prueba",
                "quantity" => 1,
                "unit_price" => 100.00,
                "currency_id" => "ARS",  // O "BRL", "USD", etc.
            ]
        ];

        // Comprador de prueba (puedes cambiarlo)
        $payer = [
            "email" => "test_user_123456@testuser.com", // Email de prueba de MP
        ];

        // Configuración básica del pago
        $preferenceData = [
            "items" => $items,
            "payer" => $payer,
            "auto_return" => "approved", // Redirige automáticamente al éxito
            "back_urls" => [
                "success" => route('payment.success'), // Ruta para éxito
                "failure" => route('payment.failure'), // Ruta para fallo
            ],
        ];

        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);
            return redirect()->away($preference->init_point); // Redirige a Checkout Pro
        } catch (MPApiException $e) {
            return back()->with('error', 'Error al crear el pago: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        return view('payment.success'); // Vista de éxito
    }

    public function failure(Request $request)
    {
        return view('payment.failure'); // Vista de fallo
    }
}
