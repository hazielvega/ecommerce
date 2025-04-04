<?php

namespace App\Observers;

use App\Models\Variant;
use Illuminate\Support\Str;

class VariantObserver
{
    public function created(Variant $variant)
    {
        $variant->sku = Str::random(12);
        // Asigno el precio de compra
        $variant->purchase_price = $variant->product->purchase_price;
        // Asigno el precio de venta
        $variant->sale_price = $variant->product->sale_price;
        // Asigno el stock
        $variant->stock = 20;
        // Guardo el cambio
        $variant->save();
    }
}
