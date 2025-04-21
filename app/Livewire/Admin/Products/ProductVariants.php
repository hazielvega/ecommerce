<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\Variant;
use App\Models\Option;
use Livewire\Component;

class ProductVariants extends Component
{
    public $product;
    public $product_features = [];
    public $selected_features = [];
    public $options;
    public $grouped_features = [];
    public $combinations = [];
    public $enabledVariants = [];

    public $variantEdit = [
        'open' => false,
        'id' => null,
        'stock' => null,
        'min_stock' => null,
        'purchase_price' => null,
        'sale_price' => null,
    ];

    public function mount($product)
    {
        $this->product = $product;
        $this->options = Option::with('features')->get();
        $this->product_features = $this->product->features->pluck('id')->toArray();
        $this->selected_features = $this->product_features;
        $this->getProductVariants();
    }

    public function getProductVariants()
    {
        $this->enabledVariants = Variant::where('product_id', $this->product->id)
            ->where('is_enabled', true)
            ->with('features')
            ->get();
    }

    private function groupFeaturesByOption()
    {
        return Feature::whereIn('id', $this->selected_features)
            ->get()
            ->groupBy('option_id')
            ->map(fn($group) => $group->pluck('id')->toArray())
            ->toArray();
    }

    private function generateCombinations($groupedFeatures)
    {
        if (count($groupedFeatures) === 1) {
            return current($groupedFeatures);
        }

        if (empty($groupedFeatures)) {
            return [];
        }

        return $this->combine(array_values($groupedFeatures));
    }

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

    public function saveProductFeatures()
    {
        $this->product->features()->sync($this->selected_features);
        $this->product = $this->product->fresh();
    }

    public function createVariants()
    {
        // Guardar las características seleccionadas
        $this->saveProductFeatures();

        // Si no hay características seleccionadas, deshabilitar todas las variantes
        if (empty($this->selected_features)) {
            Variant::where('product_id', $this->product->id)
                ->update(['is_enabled' => false]);

            $this->getProductVariants();

            $this->dispatch('swal', [
                'type' => 'info',
                'title' => 'Variantes actualizadas',
                'text' => 'Se han deshabilitado todas las variantes al no haber características seleccionadas.',
            ]);
            return;
        }

        // Generar las combinaciones de variantes
        $this->grouped_features = $this->groupFeaturesByOption();
        $this->combinations = $this->generateCombinations($this->grouped_features);

        $existingVariants = Variant::where('product_id', $this->product->id)
            ->with('features')
            ->get();

        $variantsToEnable = [];

        // Caso cuando solo hay un grupo de características (una sola opción)
        if (count($this->grouped_features) === 1) {
            foreach (current($this->grouped_features) as $featureId) {
                $variant = $existingVariants->first(fn($v) => $v->features->contains('id', $featureId));

                if ($variant) {
                    if (!$variant->is_enabled) {
                        $variant->update(['is_enabled' => true]);
                    }
                    $variantsToEnable[] = $variant->id;
                } else {
                    $newVariant = Variant::create([
                        'product_id' => $this->product->id,
                        'is_enabled' => true,
                    ]);
                    $newVariant->features()->attach($featureId);
                    $variantsToEnable[] = $newVariant->id;
                }
            }
        }
        // Caso cuando hay múltiples grupos (múltiples opciones)
        else {
            foreach ($this->combinations as $combination) {
                $variant = $existingVariants->first(function ($v) use ($combination) {
                    return $v->features->pluck('id')->sort()->values() == collect($combination)->sort()->values();
                });

                if ($variant) {
                    if (!$variant->is_enabled) {
                        $variant->update(['is_enabled' => true]);
                    }
                    $variantsToEnable[] = $variant->id;
                } else {
                    $newVariant = Variant::create([
                        'product_id' => $this->product->id,
                        'is_enabled' => true,
                    ]);
                    $newVariant->features()->attach($combination);
                    $variantsToEnable[] = $newVariant->id;
                }
            }
        }

        // Deshabilitar variantes que no están en la lista de habilitadas
        Variant::where('product_id', $this->product->id)
            ->whereNotIn('id', $variantsToEnable)
            ->update(['is_enabled' => false]);

        // Actualizar la lista de variantes
        $this->getProductVariants();

        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Variantes actualizadas',
            'text' => 'Las variantes se han actualizado correctamente.',
        ]);
    }

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

    public function updateVariant()
    {
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

        Variant::find($this->variantEdit['id'])->update([
            'stock' => $this->variantEdit['stock'],
            'min_stock' => $this->variantEdit['min_stock'],
            'purchase_price' => $this->variantEdit['purchase_price'],
            'sale_price' => $this->variantEdit['sale_price'],
        ]);

        $this->reset('variantEdit');
        $this->product = $this->product->fresh();
        $this->getProductVariants();
    }

    public function render()
    {
        return view('livewire.admin.products.product-variants');
    }
}
