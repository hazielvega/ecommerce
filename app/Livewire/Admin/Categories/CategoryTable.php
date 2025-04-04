<?php

namespace App\Livewire\Admin\Categories;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Category;
use App\Models\Product;

class CategoryTable extends DataTableComponent
{
    protected $model = Category::class;

    protected $listeners = ['categoryUpdated' => '$refresh'];


    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            // No quiero que esta columna se muestre
            Column::make("Id", "id")
                ->hideIf(1),

            // Nombre
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),

            // Cantididad de productos de la categoría
            Column::make("Productos")
                ->label(function ($row) {
                    return $row->totalProducts(); // Llamamos al accesor
                })
                ->sortable(),

            // Fecha
            Column::make("Fecha de creación", "created_at")
                ->format(function ($value) {
                    return $value->diffForHumans();
                })
                ->sortable(),

            // Boton para editar
            Column::make("Edición")
                ->label(function ($row) {
                    return view('admin.categories.actions', ['category' => $row]);
            })
        ];
    }
}
