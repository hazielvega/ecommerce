<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'option_id',
    ];

    //Relacion uno a muchos inversa
    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    // Relación muchos a muchos con variants
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'feature_variant')
            ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'feature_product');
    }
}
