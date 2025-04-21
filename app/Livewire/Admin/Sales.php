<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Exports\SalesExport;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class Sales extends Component
{
    use WithPagination;
    public $categories = [];
    public $selected_category = "";
    public $selected_subcategory = "";
    public $date_from = "";
    public $date_to = "";

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->categories = Category::all();
        $this->date_to = Carbon::today()->format('Y-m-d');
        $this->date_from = Carbon::today()->subMonth()->format('Y-m-d');
    }

    public function getSubcategoriesProperty()
    {
        return $this->selected_category
            ? Category::find($this->selected_category)?->subcategories ?? collect([])
            : collect([]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getSalesDataProperty()
    {
        $query = OrderItem::with([
            'order', // Relación básica con la orden
            'variant.product.subcategory.category', // Todas las relaciones necesarias
        ])
            ->whereHas('order', function ($q) {
                // Solo órdenes completadas
                $q->where('status', OrderStatus::Completado->value);

                // Filtro por fechas
                $q->when($this->date_from, function ($q) {
                    $q->whereDate('created_at', '>=', $this->date_from);
                })
                    ->when($this->date_to, function ($q) {
                        $q->whereDate('created_at', '<=', $this->date_to);
                    });
            })
            ->when($this->selected_category, function ($q) {
                // Filtro por categoría a través de la relación producto->subcategoría->categoría
                $q->whereHas('variant.product.subcategory.category', function ($q) {
                    $q->where('id', $this->selected_category);
                });
            })
            ->when($this->selected_subcategory, function ($q) {
                // Filtro por subcategoría a través de la relación producto->subcategoría
                $q->whereHas('variant.product.subcategory', function ($q) {
                    $q->where('id', $this->selected_subcategory);
                });
            });

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function generateReport()
    {
        $fileName = 'reporte_ventas_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new SalesExport(
            $this->selected_category ? (int)$this->selected_category : null,
            $this->selected_subcategory ? (int)$this->selected_subcategory : null,
            $this->date_from,
            $this->date_to
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.admin.sales', [
            'sales' => $this->sales_data
        ]);
    }
}
