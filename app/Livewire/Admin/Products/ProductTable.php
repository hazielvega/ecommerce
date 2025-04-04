<?php

namespace App\Livewire\Admin\Products;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;

class ProductTable extends DataTableComponent
{
    protected $model = Product::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function query()
    {
        return Product::with('subcategory.category', 'variants')
            ->selectRaw('products.*, 
                EXISTS (
                    SELECT 1 FROM variants 
                    WHERE variants.product_id = products.id 
                    AND variants.stock < variants.min_stock
                ) as has_low_stock')
            ->orderByRaw('has_low_stock DESC'); // Ordena primero los que tienen bajo stock
    }

    public function columns(): array
    {
        return [
            // ID
            Column::make("Id", "id")
                ->hideIf(1)
                ->sortable(),

            // Nombre
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),

            // Subcategoría
            Column::make("Subcategoría", "subcategory.name")
                ->sortable(),

            // Categoría
            Column::make("Categoría", "subcategory.category.name")
                ->sortable(),

            // Precio de compra
            Column::make("Precio de compra", "purchase_price")
                ->sortable(),

            // Precio de venta
            Column::make("Precio de venta", "sale_price")
                ->sortable(),

            // // **Columna de bajo stock**
            // Column::make("Bajo Stock", "has_low_stock")
            //     ->sortable()
            //     ->label(fn($row) => $row->has_low_stock ? '⚠️ Sí' : '✅ No'),

            // Variantes
            Column::make("Stock de Variantes")
                ->label(fn($row) => view('admin.products.variants', ['product' => $row])),

            // Acciones
            Column::make("Edición")
                ->label(fn($row) => view('admin.products.actions', ['product' => $row]))
                ->setColumnLabelStatusDisabled(),
        ];
    }
}



