<?php

namespace App\Exports;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB; // Añade esta línea
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents,
    ShouldAutoSize
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Border,
    Fill,
    Font,
    Alignment,
    NumberFormat
};

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    private ?int $categoryId;
    private ?int $subcategoryId;
    private ?string $dateFrom;
    private ?string $dateTo;
    private float $totalRevenue = 0;

    public function __construct(
        ?int $categoryId = null,
        ?int $subcategoryId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ) {
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = OrderItem::query()
            ->select([
                'variants.id as variant_id',  // Usar el ID en lugar de SKU para agrupar
                'products.name as product_name',
                'categories.name as category_name',
                'subcategories.name as subcategory_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            ])
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('variants', 'order_items.variant_id', '=', 'variants.id')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->where('orders.status', OrderStatus::Completed->value)
            ->groupBy([
                'variants.id',
                'products.name',
                'categories.name',
                'subcategories.name'
            ]);

        $this->applyFilters($query);

        $results = $query->get()->map(function ($item) {
            // Obtener el SKU basado en el variant_id
            $variant = \App\Models\Variant::find($item->variant_id);
            $item->sku = $variant->sku;
            return $item;
        });

        $this->totalRevenue = $results->sum('total_revenue');

        return $results;
    }

    private function applyFilters($query): void
    {
        // Filtro por rango de fechas
        $query->when($this->dateFrom, fn($q) => $q->whereDate('orders.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('orders.created_at', '<=', $this->dateTo));

        // Filtro por categoría
        $query->when($this->categoryId, fn($q) => $q->where('categories.id', $this->categoryId));

        // Filtro por subcategoría
        $query->when($this->subcategoryId, fn($q) => $q->where('subcategories.id', $this->subcategoryId));
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Producto',
            'Categoría',
            'Subcategoría',
            'Cantidad Vendida',
            'Ingresos Totales'
        ];
    }

    public function map($row): array
    {
        return [
            $row->sku,
            $row->product_name,
            $row->category_name,
            $row->subcategory_name,
            (int) $row->total_quantity,
            $row->total_revenue
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo para los datos numéricos
        $sheet->getStyle('E2:F' . ($sheet->getHighestRow()))
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Bordes para todas las celdas
        $sheet->getStyle('A1:F' . ($sheet->getHighestRow() + 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Estilo para la fila de totales
        $totalRow = $sheet->getHighestRow() + 1;
        $sheet->setCellValue('E' . $totalRow, 'Total General:');
        $sheet->setCellValue('F' . $totalRow, $this->totalRevenue);

        $sheet->getStyle('E' . $totalRow . ':F' . $totalRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '27AE60']
            ]
        ]);

        // Congelar la fila de encabezados
        $sheet->freezePane('A2');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Autoajustar columnas después de cargar los datos
                foreach (range('A', 'F') as $column) {
                    $event->sheet->getColumnDimension($column)
                        ->setAutoSize(true);
                }

                // Añadir filtros a los encabezados
                $event->sheet->setAutoFilter('A1:F1');
            }
        ];
    }
}
