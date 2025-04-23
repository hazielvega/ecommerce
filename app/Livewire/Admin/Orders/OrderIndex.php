<?php

namespace App\Livewire\Admin\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $statusFilter = 'all';
    public $dateFilter = '';
    public $perPage = 10;
    public $paymentMethodFilter = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => 'all'],
        'dateFilter' => ['except' => ''],
        'paymentMethodFilter' => ['except' => 'all'],
        'perPage' => ['except' => 10]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethodFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->dateFilter = '';
        $this->paymentMethodFilter = 'all';
        $this->resetPage();
    }

    public function getOrderStatuses()
    {
        return [
            'all' => 'Todos',
            OrderStatus::Pendiente->value => 'Pendiente',
            OrderStatus::Procesando->value => 'Procesando',
            OrderStatus::Enviado->value => 'Enviado',
            OrderStatus::Completado->value => 'Completado',
            OrderStatus::Fallido->value => 'Fallido',
            OrderStatus::Reembolsado->value => 'Reembolsado',
            OrderStatus::Cancelado->value => 'Cancelado',
        ];
    }

    public function getPaymentMethods()
    {
        return [
            'all' => 'Todos',
            1 => 'Tarjeta de Crédito',
            2 => 'Transferencia Bancaria',
            3 => 'Efectivo',
            4 => 'PayPal',
        ];
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'El estado de la orden fue actualizado correctamente.',
        ]);
    }

    // Metodo para descargar el ticket
    public function downloadTicket(Order $order)
    {
        return Storage::download($order->pdf_path);
    }

    public function render()
    {
        $today = Carbon::today()->toDateString();

        $orders = Order::with(['user', 'receiver', 'orderItems'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('receiver', function ($receiverQuery) {
                            $receiverQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->paymentMethodFilter !== 'all', function ($query) {
                $query->where('payment_method', $this->paymentMethodFilter);
            })
            ->when($this->dateFilter, function ($query) use ($today) {
                if ($this->dateFilter === 'today') {
                    $query->whereDate('created_at', $today);
                } elseif ($this->dateFilter === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($this->dateFilter === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.orders.order-index', [
            'orders' => $orders,
            'statuses' => $this->getOrderStatuses(),
            'paymentMethods' => $this->getPaymentMethods(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', OrderStatus::Pendiente->value)->count(),
            'completedOrders' => Order::where('status', OrderStatus::Completado->value)->count(),
        ]);
    }
}
