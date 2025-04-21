<?php

namespace App\Exports;

use App\Models\Variant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VariantsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filter;
    protected $subcategoryId;

    public function __construct($filter, $subcategoryId = null)
    {
        $this->filter = $filter;
        $this->subcategoryId = $subcategoryId;
    }

    public function collection()
    {
        $query = Variant::with(['product.subcategory.category', 'features']);

        // Aplicar filtros
        if ($this->filter === 'low_stock') {
            $query->whereColumn('stock', '<=', 'min_stock');
        } elseif (str_ends_with($this->filter, '_low_stock')) {
            $categoryId = str_replace('_low_stock', '', $this->filter);
            $query->whereHas('product.subcategory.category', fn($q) => $q->where('id', $categoryId))
                ->whereColumn('stock', '<=', 'min_stock');
        } elseif (is_numeric($this->filter)) {
            $query->whereHas('product.subcategory.category', fn($q) => $q->where('id', $this->filter));
        }

        if ($this->subcategoryId) {
            $query->whereHas('product', fn($q) => $q->where('subcategory_id', $this->subcategoryId));
        }

        return $query->get()->map(function ($variant) {
            return [
                'ID' => $variant->id,
                'Producto' => $variant->product->name,
                'Variante' => $variant->features->pluck('description')->implode(', '),
                'SKU' => $variant->sku,
                'Stock' => $variant->stock,
                'Stock Mínimo' => $variant->min_stock,
                'Precio' => $variant->sale_price,
                'Estado' => $variant->is_enabled ? 'Activo' : 'Inactivo',
                'Subcategoría' => $variant->product->subcategory->name ?? 'N/A',
                'Categoría' => $variant->product->subcategory->category->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Producto',
            'Variante',
            'SKU',
            'Stock',
            'Stock Mínimo',
            'Precio',
            'Estado',
            'Subcategoría',
            'Categoría'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '4B5563']]
            ],
            'E' => [
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'color' => ['rgb' => $this->filter === 'low_stock' ? 'EF4444' : '10B981']
                ]
            ],
        ];
    }
}
