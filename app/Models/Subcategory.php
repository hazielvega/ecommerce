<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
    ];

    //Relacion uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Relacion uno a muchos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accesor para contar productos en la subcategoría
    public function totalProducts(): int
    {
        return Product::where('subcategory_id', $this->id)->count();
    }

    // Relación con ofertas
    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_subcategories');
    }
}
