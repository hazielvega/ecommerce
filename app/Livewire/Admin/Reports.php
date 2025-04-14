<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reports extends Component
{
    // Filtros
    public $selected_category = null;
    public $selected_subcategory = null;
    public $date_from;
    public $date_to;

    // Estado
    public $loading = false;

    // Datos para los gráficos
    public $topProductsData = [];
    public $topSubcategoriesData = [];
    public $categoriesSalesData = [];
    public $newUsersData = [];

    protected $rules = [
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
    ];

    protected $messages = [
        'date_to.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
    ];

    public function mount()
    {
        $this->date_to = Carbon::today()->format('Y-m-d');
        $this->date_from = Carbon::today()->subMonth()->format('Y-m-d');
        $this->loadAllChartsData();
    }

    public function getSubcategoriesProperty()
    {
        if (!$this->selected_category) {
            return collect();
        }

        return Category::find($this->selected_category)?->subcategories ?? collect();
    }

    public function updated($property)
    {
        // Resetear subcategoría si cambia la categoría
        if ($property === 'selected_category') {
            $this->reset('selected_subcategory');
        }

        // Validar antes de cargar datos si son campos de fecha
        if (in_array($property, ['date_from', 'date_to'])) {
            $this->validateOnly($property);
        }

        // Cargar datos con un pequeño retraso para evitar múltiples llamadas
        $this->loadAllChartsData();
    }

    public function loadAllChartsData()
    {
        $this->loading = true;

        try {
            $this->loadTopProductsData();
            $this->loadTopSubcategoriesData();
            $this->loadCategoriesSalesData();
            $this->loadNewUsersData();
        } finally {
            $this->loading = false;
        }
    }

    public function loadTopProductsData()
    {
        $query = OrderItem::query()
            ->select([
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            ])
            ->join('variants', 'order_items.variant_id', '=', 'variants.id')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatus::Completed)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10);

        // Aplicar filtros
        $query = $this->applyProductFilters($query);
        $query = $this->applyDateFilters($query);

        $this->topProductsData = $query->get()->toArray();

        $this->dispatch('productsChartUpdated', data: $this->topProductsData);
    }

    public function loadTopSubcategoriesData()
    {
        $query = OrderItem::query()
            ->select([
                'subcategories.id',
                'subcategories.name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            ])
            ->join('variants', 'order_items.variant_id', '=', 'variants.id')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatus::Completed)
            ->groupBy('subcategories.id', 'subcategories.name', 'categories.name')
            ->orderByDesc('total_quantity')
            ->limit(10);

        // Aplicar filtros
        $query = $this->applyProductFilters($query);
        $query = $this->applyDateFilters($query);

        $this->topSubcategoriesData = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name . ' (' . $item->category_name . ')',
                'total_quantity' => $item->total_quantity,
                'category_name' => $item->category_name
            ];
        })->toArray();

        $this->dispatch('subcategoriesChartUpdated', data: $this->topSubcategoriesData);
    }

    public function loadCategoriesSalesData()
    {
        $query = OrderItem::query()
            ->select([
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            ])
            ->join('variants', 'order_items.variant_id', '=', 'variants.id')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatus::Completed)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_quantity');

        // Aplicar filtros
        $query = $this->applyProductFilters($query);
        $query = $this->applyDateFilters($query);

        $total = $query->get()->sum('total_quantity');

        $this->categoriesSalesData = $query->get()->map(function ($item) use ($total) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'total_quantity' => $item->total_quantity,
                'percentage' => $total > 0 ? round(($item->total_quantity / $total) * 100, 2) : 0
            ];
        })->toArray();

        $this->dispatch('categoriesSalesChartUpdated', data: $this->categoriesSalesData);
    }

    public function loadNewUsersData()
    {
        $query = User::query()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->groupBy('date')
            ->orderBy('date');

        // Aplicar filtros de fecha
        $query = $this->applyDateFilters($query, 'users.created_at');

        $this->newUsersData = $query->get()->map(function ($item) {
            return [
                'date' => $item->date,
                'count' => $item->count
            ];
        })->toArray();

        $this->dispatch('newUsersChartUpdated', data: $this->newUsersData);
    }

    protected function applyProductFilters($query)
    {
        return $query
            ->when($this->selected_category, function ($q) {
                $q->whereHas(
                    'variant.product.subcategory.category',
                    fn($q) => $q->where('id', $this->selected_category)
                );
            })
            ->when($this->selected_subcategory, function ($q) {
                $q->whereHas(
                    'variant.product.subcategory',
                    fn($q) => $q->where('id', $this->selected_subcategory)
                );
            });
    }

    protected function applyDateFilters($query, $dateColumn = 'orders.created_at')
    {
        return $query->whereBetween($dateColumn, [
            Carbon::parse($this->date_from)->startOfDay(),
            Carbon::parse($this->date_to)->endOfDay()
        ]);
    }

    public function render()
    {
        return view('livewire.admin.reports', [
            'categories' => Category::all(),
            'subcategories' => $this->subcategories
        ]);
    }
}
