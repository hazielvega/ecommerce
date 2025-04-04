<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class ProductsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithColumnWidths, WithStyles
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Product::with('subcategory.category')
            ->withSum('variants as total_stock', 'stock'); // Calcula el stock total
    
        // Aplicar filtros
        if ($this->filter === 'low_stock') {
            $query->having('total_stock', '<', 10);
        } elseif (is_numeric($this->filter)) {
            // Filtrar por categoría
            $query->whereHas('subcategory.category', function (Builder $q) {
                $q->where('id', $this->filter);
            });
        } elseif (str_ends_with($this->filter, '_low_stock')) {
            // Filtrar productos de una categoría específica con bajo stock
            $categoryId = str_replace('_low_stock', '', $this->filter);
            if (is_numeric($categoryId)) {
                $query->whereHas('subcategory.category', function (Builder $q) use ($categoryId) {
                    $q->where('id', $categoryId);
                })->having('total_stock', '<', 10);
            }
        }
    
        return $query->get()->map(function ($product) {
            return [
                'Código' => $product->sku,
                'Nombre' => $product->name,
                'Precio de compra' => $product->purchase_price,
                'Precio de venta' => $product->sale_price,
                'Subcategoría' => $product->subcategory->name ?? 'Sin Subcategoría',
                'Categoría' => $product->subcategory->category->name ?? 'Sin Categoría',
                'Stock Total' => $product->total_stock, // Se obtiene de `withSum`
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Precio de compra',
            'Precio de venta',
            'Subcategoría',
            'Categoría',
            'Stock Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']]],
            'A1:F1' => ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => '4CAF50']]],
            'G1' => ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'FFFF00']]],
            'A' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 25,
            'D' => 25,
            'E' => 20,
            'F' => 20,
            'G' => 15,
        ];
    }
}
