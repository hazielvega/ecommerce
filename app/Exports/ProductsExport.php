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
    protected $subcategoryId;

    public function __construct($filter, $subcategoryId = null)
    {
        $this->filter = $filter;
        $this->subcategoryId = $subcategoryId;
    }

    public function collection()
    {
        $query = Product::with(['subcategory.category', 'variants'])
            ->withSum('variants as total_stock', 'stock');
    
        // Aplicar filtros
        if ($this->filter === 'low_stock') {
            $query->having('total_stock', '<', \DB::raw('products.min_stock'));
        } 
        elseif (str_ends_with($this->filter, '_low_stock')) {
            $categoryId = str_replace('_low_stock', '', $this->filter);
            $query->whereHas('subcategory.category', fn($q) => $q->where('id', $categoryId))
                  ->having('total_stock', '<', \DB::raw('products.min_stock'));
        } 
        elseif (is_numeric($this->filter)) {
            $query->whereHas('subcategory.category', fn($q) => $q->where('id', $this->filter));
        }

        if ($this->subcategoryId) {
            $query->where('subcategory_id', $this->subcategoryId);
        }
    
        return $query->get()->map(function ($product) {
            return [
                'ID' => $product->id,
                'Nombre' => $product->name,
                'Descripción' => $product->description,
                'Precio Compra' => $product->purchase_price,
                'Precio Venta' => $product->sale_price,
                'Stock Total' => $product->total_stock,
                'Stock Mínimo' => $product->min_stock,
                'Estado' => $product->is_enabled ? 'Activo' : 'Inactivo',
                'Subcategoría' => $product->subcategory->name ?? 'N/A',
                'Categoría' => $product->subcategory->category->name ?? 'N/A',
                'Fecha Creación' => $product->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Precio Compra',
            'Precio Venta',
            'Stock Total',
            'Stock Mínimo',
            'Estado',
            'Subcategoría',
            'Categoría',
            'Fecha Creación'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '4B5563']]
            ],
            'A:K' => ['alignment' => ['wrapText' => true]],
            'H' => [
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid', 
                    'color' => ['rgb' => $this->filter === 'low_stock' ? 'EF4444' : '10B981']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, 'B' => 30, 'C' => 40, 'D' => 15, 
            'E' => 15, 'F' => 15, 'G' => 15, 'H' => 12,
            'I' => 20, 'J' => 20, 'K' => 15
        ];
    }
}