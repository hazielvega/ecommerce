<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'purchase_price',
        'sale_price',
        'stock',
        'min_stock',
        'is_enabled',
        'product_id',
    ];

    // Accesor: nos permite agregar un atributo al modelo
    // Cada vez que llame al metodo image() me va a retornar url de la imagen dependiento de lo que está 
    // guardado en el image_path
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->image_path ? Storage::url($this->image_path) : asset('img/noimage.png'),
        );
    }

    //Relacion uno a muchos inversa
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación muchos a muchos con features
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_variant')
            ->withTimestamps();
    }


    // Relacion con orderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Verificar si el stock es menor al minimo
    public function isOutOfStock(): bool
    {
        return $this->stock < $this->min_stock;
    }
}
