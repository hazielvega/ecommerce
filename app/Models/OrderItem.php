<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'variant_id',
        'offer_id',
        'quantity',
        'price',
        'original_price',
        'discount_percentage',
        'subtotal',
    ];

    // Relación inversa con Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación inversa con Variant
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}

