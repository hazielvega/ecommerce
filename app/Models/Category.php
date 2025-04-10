<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // 'family_id',
    ];


    //Relacion uno a muchos
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // Accesor para contar productos en todas las subcategorías de la categoría
    public function totalProducts(): int
    {
        return Product::whereHas('subcategory', function ($query) {
            $query->where('category_id', $this->id);
        })->count();
    }

}
