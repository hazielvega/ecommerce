<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'provincia',
        'ciudad',
        'receiver',
        'receiver_info',
        'is_shipping',
        'is_billing',
    ];

    protected $casts = [
        'receiver_info' => 'array',
        'default' => 'boolean',
    ];
}
