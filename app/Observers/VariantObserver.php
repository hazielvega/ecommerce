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
        // Asigno el stock minimo
        $variant->min_stock = $variant->product->min_stock;
        // Guardo el cambio
        $variant->save();
    }
}
