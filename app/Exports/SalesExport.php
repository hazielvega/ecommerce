<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $categoryId;
    protected $subcategoryId;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($categoryId, $subcategoryId, $dateFrom, $dateTo)
    {
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = OrderItem::select(
            'variants.id as product_code',
            'products.name as product_name',
            'categories.name as category_name',
            'subcategories.name as subcategory_name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.subtotal) as total_subtotal')
        )
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('variants', 'order_items.variant_id', '=', 'variants.id')
        ->join('products', 'variants.product_id', '=', 'products.id')
        ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
        ->join('categories', 'subcategories.category_id', '=', 'categories.id')
        ->where('orders.status', 4); // Solo órdenes completadas

        // Aplicar filtros
        if ($this->dateFrom) {
            $query->whereDate('orders.created_at', '>=', Carbon::parse($this->dateFrom));
        }

        if ($this->dateTo) {
            $query->whereDate('orders.created_at', '<=', Carbon::parse($this->dateTo));
        }

        if ($this->categoryId) {
            $query->where('categories.id', $this->categoryId);
        }

        if ($this->subcategoryId) {
            $query->where('subcategories.id', $this->subcategoryId);
        }

        return $query->groupBy('variants.id', 'products.name', 'categories.name', 'subcategories.name')->get();
    }

    public function headings(): array
    {
        return [
            'Código del Producto',
            'Nombre del Producto',
            'Categoría',
            'Subcategoría',
            'Cantidad Vendida',
            'Subtotal ($)'
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // Encabezados en negrita y con fondo
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'D3D3D3']
            ]
        ]);

        // Ajustar ancho de columnas automáticamente
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Bordes para las celdas
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:G$highestRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ]
            ]
        ]);
    }

    public function map($row): array
    {
        return [
            $row->product_code,
            $row->product_name,
            $row->category_name,
            $row->subcategory_name,
            $row->total_quantity,
            number_format($row->total_subtotal, 2, ',', '.') . ' $'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->append(
                    ['Total de Ingresos:', '', '', '', '', number_format($this->collection()->sum('total_subtotal'), 2, ',', '.') . ' $'],
                    config('excel.exports.default_styles.summary_style')
                );
            }
        ];
    }
}
