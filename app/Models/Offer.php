<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
    ];

    // Relación con productos
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'offer_products');
    }
}
