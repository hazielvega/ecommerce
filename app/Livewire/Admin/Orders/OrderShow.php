<?php

namespace App\Livewire\Admin\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\DB;

class OrderShow extends Component
{
    public Order $order;
    public $newStatus;
    public $statusNote;

    public function mount(Order $order)
    {
        $this->order = $order->load([
            'user',
            'orderItems.variant.product',
            'orderItems.offer',
            'shippingAddress',
            'billingAddress',
            'statusHistory.changedBy'
        ]);
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::findOrFail($orderId);
        $previousStatus = $order->status;

        // Validar que el nuevo estado sea diferente
        if ($previousStatus->value == $newStatus) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes seleccionar el mismo estado actual.',
            ]);
            return;
        }

        DB::transaction(function () use ($order, $newStatus, $previousStatus) {
            $order->update(['status' => $newStatus]);

            // Disparar evento con ambos estados
            event(new \App\Events\OrderStatusUpdated(
                $order,
                $previousStatus->value, // Enviar el valor numérico
                $newStatus
            ));

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Actualizado',
                'text' => 'El estado de la orden fue actualizado correctamente.',
            ]);
        });
    }

    protected function statusColor($status): string
    {
        return match ($status) {
            OrderStatus::Pendiente->value => 'bg-yellow-500/20 text-yellow-500',
            OrderStatus::Procesando->value => 'bg-blue-500/20 text-blue-500',
            OrderStatus::Enviado->value => 'bg-indigo-500/20 text-indigo-500',
            OrderStatus::Fallido->value => 'bg-red-500/20 text-red-500',
            OrderStatus::Completado->value => 'bg-green-500/20 text-green-500',
            OrderStatus::Cancelado->value => 'bg-red-500/20 text-red-500',
            OrderStatus::Reembolsado->value => 'bg-gray-500/20 text-gray-500',
            default => 'bg-gray-500/20 text-gray-500'
        };
    }

    protected function statusName($status): string
    {
        return match ($status) {
            OrderStatus::Pendiente->value => 'Pendiente',
            OrderStatus::Procesando->value => 'Procesando',
            OrderStatus::Enviado->value => 'Enviado',
            OrderStatus::Fallido->value => 'Fallido',
            OrderStatus::Completado->value => 'Completado',
            OrderStatus::Cancelado->value => 'Cancelado',
            OrderStatus::Reembolsado->value => 'Reembolsado',
            default => 'Desconocido'
        };
    }

    public function render()
    {
        return view('livewire.admin.orders.order-show', [
            'statuses' => OrderStatus::cases(),
            'statusName' => fn($status) => $this->statusName($status),
            'statusColor' => fn($status) => $this->statusColor($status),
        ]);
    }
}
