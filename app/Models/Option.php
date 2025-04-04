<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    // Método para verificar si las opciones pertenecen a una determinada familia
    public function scopeVerifyFamily($query, $family_id)
    {
        $query->when($family_id, function ($query, $family_id) {
            // Filtra las opciones por la familia seleccionada
            $query->whereHas('features.products.subcategory.category', function ($query) use ($family_id) {
                $query->where('family_id', $family_id);
            })->with([
                'features' => function ($query) use ($family_id) {
                    // Filtra las características relacionadas con los productos de la familia seleccionada
                    $query->whereHas('products.subcategory.category', function ($query) use ($family_id) {
                        $query->where('family_id', $family_id);
                    });
                }
            ]);
        });
    }

    /**
     * Filtra opciones que pertenecen a productos en las ofertas especificadas
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|Collection $offerIds Array o colección de IDs de ofertas
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInOffers($query, $offerIds)
    {
        return $query->when(!empty($offerIds), function ($query) use ($offerIds) {
            // Filtra las opciones por ofertas
            $query->whereHas('features.products.offers', function ($query) use ($offerIds) {
                $query->whereIn('offers.id', (array)$offerIds);
            })->with([
                'features' => function ($query) use ($offerIds) {
                    // Filtra las características relacionadas con productos en ofertas
                    $query->whereHas('products.offers', function ($query) use ($offerIds) {
                        $query->whereIn('offers.id', (array)$offerIds);
                    })->with(['products' => function ($query) use ($offerIds) {
                        // Carga los productos que están en las ofertas
                        $query->whereHas('offers', function ($query) use ($offerIds) {
                            $query->whereIn('offers.id', (array)$offerIds);
                        });
                    }]);
                }
            ]);
        });
    }

    // Método para verificar si las opciones pertenecen a una determinada categoría
    public function scopeVerifyCategory($query, $category_id)
    {
        $query->when($category_id, function ($query, $category_id) {
            // Filtra las opciones por la categoría seleccionada
            $query->whereHas('features.products.subcategory', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })->with([
                'features' => function ($query) use ($category_id) {
                    // Filtra las características relacionadas con los productos de la categoría seleccionada
                    $query->whereHas('products.subcategory', function ($query) use ($category_id) {
                        $query->where('category_id', $category_id);
                    });
                }
            ]);
        });
    }

    // Método para verificar si las opciones pertenecen a una determinada subcategoría
    public function scopeVerifySubcategory($query, $subcategory_id)
    {
        $query->when($subcategory_id, function ($query, $subcategory_id) {
            // Filtra las opciones por la subcategoría seleccionada
            $query->whereHas('features.products', function ($query) use ($subcategory_id) {
                $query->where('subcategory_id', $subcategory_id);
            })->with([
                'features' => function ($query) use ($subcategory_id) {
                    // Filtra las características relacionadas con los productos de la subcategoría seleccionada
                    $query->whereHas('products', function ($query) use ($subcategory_id) {
                        $query->where('subcategory_id', $subcategory_id);
                    });
                }
            ]);
        });
    }

    // Método para verificar si las opciones pertenecen a productos con un nombre que coincida con el buscador
    public function scopeVerifySearch($query, $search)
    {
        $query->when($search, function ($query, $search) {
            // Filtra las opciones relacionadas con productos cuyo nombre coincida con el buscador
            $query->whereHas('features.products', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })->with([
                'features' => function ($query) use ($search) {
                    // Filtra las características relacionadas con los productos cuyo nombre coincida con el buscador
                    $query->whereHas('products', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                    });
                }
            ]);
        });
    }


    //Relacion uno a muchos
    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}


/*Este código pertenece a una relación entre modelos en Laravel (un framework PHP), específicamente utilizando el método `belongsToMany` para definir una relación de muchos a muchos entre dos modelos.

### Desglose Detallado:
- **Método `products()`**:
    - Define una relación entre el modelo actual y el modelo `Product`. El nombre del método sugiere que una instancia del modelo actual puede estar relacionada con múltiples productos.

- **`return $this->belongsToMany(Product::class)`**:
    - `belongsToMany()`: Es un método de Eloquent (ORM de Laravel) que define una relación de muchos a muchos entre dos modelos.
    - `Product::class`: Indica que la relación es con el modelo `Product`. Esto significa que el modelo actual (en el que se define este método) está relacionado con el modelo `Product` a través de una tabla intermedia.

- **`->using(OptionProduct::class)`**:
    - Especifica que se utilizará una clase de modelo personalizada para la tabla intermedia de la relación. En este caso, `OptionProduct` es el modelo que representa la tabla pivote.
    - Esto es útil si necesitas agregar lógica personalizada o métodos adicionales para gestionar la relación entre `Option` y `Product` (por ejemplo, validaciones o acciones específicas).

- **`->withPivot('features')`**:
    - Indica que, al recuperar datos de la relación, también se debe incluir el campo `features` de la tabla pivote.
    - `features` es una columna en la tabla intermedia que guarda información adicional sobre la relación entre el modelo actual y `Product`.
    - Esto permite acceder a este dato extra cuando se interactúa con la relación, facilitando la gestión de propiedades específicas de cada producto relacionado.

- **`->withTimestamps()`**:
    - Indica que se deben manejar automáticamente las marcas de tiempo (`created_at` y `updated_at`) en la tabla pivote.
    - Esto es útil si deseas saber cuándo se creó o actualizó la relación entre el modelo actual y un producto.

### Resumen del Comportamiento:
Este método `products()` establece que un modelo (por ejemplo, `Option`) puede estar relacionado con muchos productos (`Product`). Utiliza una tabla intermedia, representada por el modelo `OptionProduct`, que incluye la columna `features` y guarda la fecha y hora de creación y actualización de cada relación. Esto es útil para modelos que necesitan gestionar información extra acerca de la relación, como características específicas que se aplican a cada producto en la relación. 

Por ejemplo, si `Option` representa opciones disponibles para productos, podrías tener un registro en la tabla pivote que indique qué características específicas (`features`) se aplican a cada producto cuando se asocia a una determinada opción. */