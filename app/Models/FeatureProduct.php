<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


// ESTE MODELO SE UTILIZA PARA LA TABLA INTERMEDIA
class FeatureProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'feature_id'];

    // Relacion con products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relacion con features
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
