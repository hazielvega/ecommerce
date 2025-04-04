<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'image_path',
        'purchase_price',
        'sale_price',
        'stock',
        'subcategory_id',
        'is_enabled',
    ];

    // Verificar si alguna variante necesita reabastecerse
    public function isOutOfStock()
    {
        return $this->variants->some(function ($variant) {
            return $variant->isOutOfStock();
        });
    }

    public function scopeInOffers($query, $offerIds)
    {
        return $query->when(!empty($offerIds), function ($query) use ($offerIds) {
            $query->whereHas('offers', function ($query) use ($offerIds) {
                $query->whereIn('offers.id', (array)$offerIds);
            });
        });
    }

    // Metodo para verificar si el producto pertenece a una determinada categoría
    public function scopeVerifyCategory($query, $category_id)
    {
        $query->when($category_id, function ($query, $category_id) {
            // Filtra los productos por la categoría seleccionada
            $query->whereHas('subcategory', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        });
    }

    // Metodo para verificar si el producto pertenece a una determinada subcategoría
    public function scopeVerifySubcategory($query, $subcategory_id)
    {
        $query->when($subcategory_id, function ($query, $subcategory_id) {
            // Filtra los productos por la subcategoría seleccionada
            $query->where('subcategory_id', $subcategory_id);
        });
    }

    // Metodo para el ordenamiento en los filtros
    public function scopeCustomOrder($query, $orderBy)
    {
        $query->when($orderBy == 1, function ($query) {
            $query->orderBy('created_at', 'desc'); // Más recientes primero
        })
            ->when($orderBy == 2, function ($query) {
                $query->orderBy('sale_price', 'desc'); // Precio más alto primero
            })
            ->when($orderBy == 3, function ($query) {
                $query->orderBy('sale_price', 'asc'); // Precio más bajo primero
            });
    }

    // Metodo para verificar si el nombre del producto coincide con el buscador
    public function scopeVerifySearch($query, $search)
    {
        $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    // Accesor: quiero que tome el image_path y me lo convierta en url
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url(json_decode($this->image_path)[0]),
        );
    }
    public function images(): Attribute
    {
        return Attribute::make(
            get: fn() => collect(json_decode($this->image_path ?? '[]'))->map(fn($path) => Storage::url($path))->toArray(),
        );
    }

    // Calculo de stock
    public function totalStock(): int
    {
        return $this->variants->sum('stock');
    }

    public function activeOffer()
    {
        return Offer::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where(function ($query) {
                $query->whereHas('products', function ($q) {
                    $q->where('product_id', $this->id);
                });
            })
            ->orderByDesc('discount_percentage')
            ->first();
    }

    public function getSalePriceWithDiscountAttribute()
    {
        $offer = $this->activeOffer();
        if ($offer) {
            return round($this->sale_price * (1 - $offer->discount_percentage / 100), 2);
        }
        return $this->sale_price;
    }

    //Relacion uno a muchos inversa
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_products');
    }

    //Relacion uno a muchos
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    // Relación muchos a muchos con Feature a través de feature_product
    public function features()
    {
        return $this->belongsToMany(Feature::class)->withTimestamps();
    }

    public function options()
    {
        return Option::whereHas('features', function ($query) {
            $query->whereIn('id', $this->features()->pluck('features.id'));
        })->get();
    }
}
