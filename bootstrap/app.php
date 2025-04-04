<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use App\Models\Offer;

return Application::configure(basePath: dirname(__DIR__))
    // Configura la aplicación con la ruta base definida en el directorio padre.
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',  // Define las rutas web de la aplicación.
        api: __DIR__ . '/../routes/api.php',  // Define las rutas de la API.
        commands: __DIR__ . '/../routes/console.php',  // Define las rutas para comandos de consola.
        health: '/up',  // Configura una ruta de verificación de estado (health check) en '/up'.
        // Configura rutas adicionales dentro de una función de callback.
        then: function () {
            Route::middleware('web', 'auth')  // Aplica los middleware 'web' y 'auth' a las rutas.
                ->prefix('admin')  // Agrega el prefijo 'admin' a las rutas.
                ->name('admin.')  // Asigna el prefijo 'admin.' a los nombres de las rutas.
                ->group(base_path('routes/admin.php'));  // Carga el grupo de rutas desde 'admin.php'.
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configura el middleware de la aplicación.
        $middleware->validateCsrfTokens(except: [
            'checkout/paid',  // Excluye la ruta 'checkout/paid' de la validación de tokens CSRF.
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configura el manejo de excepciones. (Actualmente vacío)
    })
    ->create();  // Crea y devuelve la instancia de la aplicación configurada.

Schedule::command(function () {
    // Activar ofertas cuya fecha de inicio haya llegado y no estén activas
    Offer::where('is_active', false)
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->update(['is_active' => true]);

    // Desactivar ofertas cuya fecha de fin haya pasado
    Offer::where('is_active', true)
        ->where('end_date', '<', now())
        ->update(['is_active' => false]);
})->everyMinute(); // Se ejecutará cada minuto
