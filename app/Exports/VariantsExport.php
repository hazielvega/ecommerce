<?php

namespace App\Exports;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VariantsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Variant::with('product.subcategory.category');

        // Aplicar filtros
        if ($this->filter === 'low_stock') {
            $query->whereColumn('stock', '<', 'min_stock');
        } elseif (is_numeric($this->filter)) {
            // Filtrar por categoría
            $query->whereHas('product.subcategory.category', function (Builder $q) {
                $q->where('id', $this->filter);
            });
        } elseif (str_ends_with($this->filter, '_low_stock')) {
            // Filtrar variantes de una categoría específica con bajo stock
            $categoryId = str_replace('_low_stock', '', $this->filter);
            if (is_numeric($categoryId)) {
                $query->whereHas('product.subcategory.category', function (Builder $q) use ($categoryId) {
                    $q->where('id', $categoryId);
                })->whereColumn('stock', '<', 'min_stock');
            }
        }

        return $query->get()->map(function ($variant) {
            return [
                'Producto Principal' => $variant->product->name ?? 'Sin Producto',
                'Variante' => $variant->features()->pluck('description')->implode(', '),
                'Stock' => $variant->stock,
                'Precio de Venta' => $variant->sale_price,
                'Subcategoría' => $variant->product->subcategory->name ?? 'Sin Subcategoría',
                'Categoría' => $variant->product->subcategory->category->name ?? 'Sin Categoría',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Producto Principal',
            'Variante',
            'Stock',
            'Precio de Venta',
            'Subcategoría',
            'Categoría'
        ];
    }
}
