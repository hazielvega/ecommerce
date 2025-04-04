<?php

namespace App\Providers;

use App\Models\Cover;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Observers\CoverObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Observers\VariantObserver;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Cuando ocurra algo en el modelo cover se va a ejecutar alguna acción
        Cover::observe(CoverObserver::class);

        Order::observe(OrderObserver::class);

        Variant::observe(VariantObserver::class);

        User::observe(UserObserver::class);
    }
}
