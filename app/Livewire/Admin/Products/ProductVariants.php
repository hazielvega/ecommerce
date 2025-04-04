<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\FeatureProduct;
use App\Models\Option;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
// ESTE COMPONENTE SE UTILIZA PARA LA GENERACION DE UNA VARIANTE PARA UN PRODUCTO
class ProductVariants extends Component
{

    public $product;
    public $product_features = [];

    // Almacena todas las caracteristicas seleccionadas
    public $selected_features = [];

    // Recupera todas las opciones de la base de datos
    public $options;

    public $grouped_features = [];

    public $combinations = [];

    // Almacena todas las variantes habilitadas del producto
    public $enabledVariants = [];

    // Variable para el modal de edicion de una variante
    public $variantEdit = [
        'open' => false,        #variable para controlar el modal
        'id' => null,           #id de la variante a editar
        'stock' => null,        #stock de la variante
        'min_stock' => null,    #stock minimo de la variante
        'purchase_price' => null, #precio de compra de la variante
        'sale_price' => null,   #precio de venta de la variante
    ];

    public function mount($product)
    {
        $this->product = $product;
        $this->options = Option::all();
        $this->product_features = $this->product->features->pluck('id')->toArray();
        $this->selected_features = $this->product_features;
        $this->getProductVariants();
    }

    // Metodo para recuperar todas las variantes habilitas del producto
    public function getProductVariants()
    {
        $this->enabledVariants = Variant::where('product_id', $this->product->id)
            ->where('is_enabled', true)
            ->get();
    }

    private function groupFeaturesByOption()
    {
        $grouped = []; // Inicializamos el array para agrupar las características

        foreach ($this->selected_features as $featureId) {
            // Recuperamos la característica por su ID
            $feature = Feature::find($featureId);

            if ($feature) {
                // Agrupamos por el option_id de la característica
                $grouped[$feature->option_id][] = $featureId;
            }
        }

        return $grouped; // Devuelve un array agrupado por option_id
    }

    // Metodo para generar las combinaciones
    private function generateCombinations($groupedFeatures)
    {
        // Si solo hay un grupo, devuelve sus elementos directamente
        if (count($groupedFeatures) === 1) {
            return $this->grouped_features;
        } elseif (count($groupedFeatures) === 0) {
            return [];
        }

        // Convertimos los valores del array asociativo en un array indexado
        $arrays = array_values($groupedFeatures);

        // Generamos las combinaciones recursivamente
        return $this->combine($arrays);
    }

    // Método recursivo para generar combinaciones
    private function combine($arrays, $prefix = [])
    {
        if (empty($arrays)) {
            return [$prefix];
        }

        $result = [];
        $firstArray = array_shift($arrays);

        foreach ($firstArray as $value) {
            $result = array_merge($result, $this->combine($arrays, array_merge($prefix, [$value])));
        }

        return $result;
    }

    // Metodo para guardar las características seleccionadas en feature_product
    public function saveProductFeatures()
    {
        //Sincroniza las características seleccionadas en la tabla intermedia
        $this->product->features()->sync($this->selected_features);

        //Actualiza el modelo para reflejar los cambios en Livewire
        $this->product = $this->product->fresh();
    }



    public function createVariants()
    {
        // Llamo al método para guardar las características del producto
        $this->saveProductFeatures();

        // Agrupo las características seleccionadas por opción
        $this->grouped_features = $this->groupFeaturesByOption();

        // Si no hay características seleccionadas, no se generan variantes
        if (empty($this->grouped_features)) {
            return;
        }

        // Genero todas las combinaciones posibles de características
        $this->combinations = $this->generateCombinations($this->grouped_features);

        // Recupero todas las variantes existentes del producto
        $existingVariants = Variant::where('product_id', $this->product->id)->get();

        // Lista de variantes que deberían estar habilitadas
        $variantsToEnable = [];

        // Si todas las características pertenecen a la misma opción
        if (count($this->grouped_features) === 1) {
            $singleOptionFeatures = array_values($this->grouped_features)[0];

            foreach ($singleOptionFeatures as $featureId) {
                // Busco si la variante ya existe con esta característica
                $variant = $existingVariants->first(function ($v) use ($featureId) {
                    return $v->features()->where('features.id', $featureId)->exists();
                });

                if ($variant) {
                    // Si la variante está deshabilitada, la habilito
                    if (!$variant->is_enabled) {
                        $variant->update(['is_enabled' => true]);
                    }
                    // Registro que esta variante debe mantenerse habilitada
                    $variantsToEnable[] = $variant->id;
                } else {
                    // Creo la nueva variante habilitada
                    $newVariant = Variant::create([
                        'product_id' => $this->product->id,
                        'is_enabled' => true,
                    ]);

                    // Asocio la variante con la característica
                    $newVariant->features()->attach($featureId);

                    // Registro la nueva variante como habilitada
                    $variantsToEnable[] = $newVariant->id;
                }
            }
        } else {
            // Si hay características de distintas opciones, genero combinaciones
            foreach ($this->combinations as $combination) {
                // Busco si ya existe una variante con exactamente las mismas características
                $variant = $existingVariants->first(function ($v) use ($combination) {
                    return $v->features()->whereIn('features.id', $combination)->count() === count($combination);
                });

                if ($variant) {
                    // Si la variante existe pero está deshabilitada, la habilito
                    if (!$variant->is_enabled) {
                        $variant->update(['is_enabled' => true]);
                    }
                    // Registro que esta variante debe mantenerse habilitada
                    $variantsToEnable[] = $variant->id;
                } else {
                    // Creo la nueva variante habilitada
                    $newVariant = Variant::create([
                        'product_id' => $this->product->id,
                        'is_enabled' => true,
                    ]);

                    // Asocio la variante con la combinación de características
                    $newVariant->features()->attach($combination);

                    // Registro la nueva variante como habilitada
                    $variantsToEnable[] = $newVariant->id;
                }
            }
        }

        // Deshabilitar variantes que no están en la lista de variantes activas
        Variant::where('product_id', $this->product->id)
            ->whereNotIn('id', $variantsToEnable)
            ->update(['is_enabled' => false]);

        // Actualizo la lista de variantes habilitadas
        $this->getProductVariants();

        // Mensaje de exito
        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Variantes creadas',
            'text' => 'Las variantes se han creado correctamente.',
        ]);
    }

    // Metodo para editar una variante
    public function editVariant($variant)
    {
        $this->variantEdit = [
            'open' => true,
            'id' => $variant['id'],
            'stock' => $variant['stock'],
            'min_stock' => $variant['min_stock'],
            'purchase_price' => $variant['purchase_price'],
            'sale_price' => $variant['sale_price'],
        ];
    }

    // Metodo para editar una variante
    public function updateVariant()
    {
        // validaciones
        $this->validate([
            'variantEdit.stock' => 'required|numeric|min:0',
            'variantEdit.min_stock' => 'required|numeric',
            'variantEdit.purchase_price' => 'required|numeric',
            'variantEdit.sale_price' => 'required|numeric',
        ], [], [
            'variantEdit.stock' => 'stock',
            'variantEdit.min_stock' => 'nivel de stock',
            'variantEdit.purchase_price' => 'precio de compra',
            'variantEdit.sale_price' => 'precio de venta',
        ]);

        // Busco la variante
        $variant = Variant::find($this->variantEdit['id']);

        // Actualizo la variante
        $variant->update([
            'stock' => $this->variantEdit['stock'],
            'min_stock' => $this->variantEdit['min_stock'],
            'purchase_price' => $this->variantEdit['purchase_price'],
            'sale_price' => $this->variantEdit['sale_price'],
        ]);

        // Reseteo la variable de edicion
        $this->reset('variantEdit');

        // Actualizo el producto
        $this->product = $this->product->fresh();

        // Actualizo la lista de variantes habilitadas
        $this->getProductVariants();
    }


    public function render()
    {
        return view('livewire.admin.products.product-variants');
    }
}
