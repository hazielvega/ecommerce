<?php

namespace App\Livewire\Admin\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderShow extends Component
{
    public Order $order;
    public $newStatus;
    public $statusNote;

    // protected $listeners = ['statusUpdated' => '$refresh'];

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

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|integer|in:' . implode(',', OrderStatus::getValues()),
            'statusNote' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () {
            $previousStatus = $this->order->status;

            $this->order->update([
                'status' => $this->newStatus,
            ]);

            // Registrar el cambio de estado en el historial
            $this->order->statusHistory()->create([
                'from_status' => $previousStatus,
                'to_status' => $this->newStatus,
                'notes' => $this->statusNote,
                'changed_by' => auth()->id(),
            ]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Actualizado',
                'text' => 'El estado de la orden fue actualizado correctamente.',
            ]);

            $this->reset(['newStatus', 'statusNote']);
        });
    }

    protected function statusName($status): string
    {
        return match ($status) {
            OrderStatus::Pending->value => 'Pendiente',
            OrderStatus::Processing->value => 'Procesando',
            OrderStatus::Shipped->value => 'Enviado',
            OrderStatus::Failed->value => 'Fallido',
            OrderStatus::Completed->value => 'Completado',
            OrderStatus::Cancelled->value => 'Cancelado',
            OrderStatus::Refunded->value => 'Reembolsado',
            default => 'Desconocido'
        };
    }

    protected function statusColor($status): string
    {
        return match ($status) {
            OrderStatus::Pending->value => 'bg-yellow-500/20 text-yellow-500',
            OrderStatus::Processing->value => 'bg-blue-500/20 text-blue-500',
            OrderStatus::Shipped->value => 'bg-indigo-500/20 text-indigo-500',
            OrderStatus::Failed->value => 'bg-red-500/20 text-red-500',
            OrderStatus::Completed->value => 'bg-green-500/20 text-green-500',
            OrderStatus::Cancelled->value => 'bg-red-500/20 text-red-500',
            OrderStatus::Refunded->value => 'bg-gray-500/20 text-gray-500',
            default => 'bg-gray-500/20 text-gray-500'
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
