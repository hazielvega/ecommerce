<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


// ESTE MODELO SE UTILIZA PARA LA TABLA INTERMEDIA
class FeatureVariant extends Model
{
    use HasFactory;

    protected $fillable = ['variant_id', 'feature_id'];

    // Relacion con variants
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    // Relacion con features
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
