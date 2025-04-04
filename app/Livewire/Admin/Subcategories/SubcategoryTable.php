<?php

namespace App\Livewire\Admin\Subcategories;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Subcategory;

class SubcategoryTable extends DataTableComponent
{
    protected $model = Subcategory::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        // Disparar el evento después de cada carga de datos
        $this->dispatch('subcategoryTableUpdated');
    }

    protected function updatedQueryString()
    {
        $this->dispatch('subcategoryTableUpdated'); // Emitir evento cuando se refresque la tabla
    }


    public function columns(): array
    {
        return [
            // No quiero que esta columna se muestre
            Column::make("Id", "id")
                ->hideIf(1)
                ->sortable(),
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),
            // Categoria
            Column::make("Categoria", "category.name")
                ->sortable(),

            // Cantididad de productos de la subcategoría
            Column::make("Productos")
                ->label(function ($row) {
                    return $row->totalProducts(); // Llamamos al accesor
                })
                ->sortable(),

            // Boton para editar
            Column::make("Edición")
                ->label(function ($row) {
                    return view('admin.subcategories.actions', ['subcategory' => $row]);
                })
        ];
    }
}
