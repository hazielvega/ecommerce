<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Events\OrderStatusUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'status'
    ];

    protected $casts = [
        'content' => 'array',
        'address' => 'array',
        'status' => OrderStatus::class
    ];

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->isDirty('status')) {
                event(new OrderStatusUpdated(
                    $order,
                    $order->getOriginal('status')
                ));
            }
        });
    }


    // Relacion uno a muchos con user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacion uno a muchos con OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relacion con Address
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }


    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
}
