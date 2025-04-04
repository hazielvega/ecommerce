<?php

namespace App\Livewire\Admin\Offers;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Offer;

class OfferTable extends DataTableComponent
{
    protected $model = Offer::class;
    public $open = false;
    // public $offer;

    // public function mount(Offer $offer)
    // {
    //     // $this->offer = $offer;
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(1),
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),
            Column::make("Descripción", "description")
                ->sortable()
                ->searchable(),
            Column::make("Descuento", "discount_percentage")
                ->format(function ($value) {
                    return $value . '%';
                })
                ->sortable(),
            Column::make("Fecha de inicio", "start_date")
                ->sortable(),
            Column::make("Fecha de fin", "end_date")
                ->sortable(),
            Column::make("Estado", "is_active")
                ->format(function ($value) {
                    return $value ? 'Activo' : 'Inactivo';
                })
                ->sortable(),

            Column::make("Edición")
                ->label(function ($row) {
                    return view('admin.offers.actions', ['offer' => $row]);
                })
        ];
    }
}
