<?php

namespace App\Livewire\Admin\Orders;

use App\Enums\OrderStatus;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class OrderTable extends DataTableComponent
{
    // Recupera los datos del modelo Order
    protected $model = Order::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            // Numero de orden
            Column::make("N° orden", "id")
                ->sortable()
                ->searchable(),
            // Ticket. Icono de pdf
            Column::make("Ticket")
                ->label(function ($row) {
                    return view('admin.orders.ticket', ['order' => $row]);
                }),
            //Fecha 
            Column::make("Fecha", "created_at")
                ->format(function ($value) {
                    return $value->format('d/m/Y');
                })
                ->sortable(),
            // Total
            Column::make("total")
                ->format(function ($value) {
                    return '$' . number_format($value, 2);
                })
                ->sortable(),
            // Status
            Column::make("Status", "status")
                ->format(function ($value) {
                    return $value->name;
                })
                ->sortable(),
            // Acciones
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.orders.actions', ['order' => $row]);
                }),
            // Ver
            Column::make("Ver")
                ->label(function ($row) {
                    return view('admin.orders.actionShow', ['order' => $row]);
                }),
        ];
    }

    // Metodo para descargar el ticket
    public function downloadTicket(Order $order)
    {
        return Storage::download($order->pdf_path);
    }

    // Metodo para cambiar el status de una orden a proccessing
    public function markAsProcessing(Order $order)
    {
        $order->status = OrderStatus::Processing;
        $order->save();
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        $this->dispatch('notify', 'El estado de la orden fue actualizado correctamente.');
    }
}
