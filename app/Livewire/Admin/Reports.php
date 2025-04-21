<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Offer;
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
    public $offersPerformanceData = [];
    public $offersByCategoryData = [];
    public $mostDiscountedCategory = [];
    public $highestAvgDiscount = [];
    public $mostDiscountedProductsCategory = [];

    // protected $listeners = [
    //     'offersChartUpdated' => 'refreshOffersCharts',
    //     'offersByCategoryChartUpdated' => 'refreshOffersByCategoryCharts'
    // ];

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
            $this->loadOffersPerformanceData();
            $this->loadOffersByCategoryData();
        } finally {
            $this->loading = false;
        }
    }

    public function loadOffersByCategoryData()
    {
        $data = Offer::query()
            ->select([
                'categories.id as category_id',
                'categories.name as category_name', // Cambiado de 'category' a 'category_name'
                DB::raw('COUNT(DISTINCT offers.id) as offers_count'),
                DB::raw('COUNT(DISTINCT products.id) as products_count'),
                DB::raw('AVG(offers.discount_percentage) as avg_discount'),
                DB::raw('SUM(order_items.quantity) as total_items_sold')
            ])
            ->join('offer_products', 'offers.id', '=', 'offer_products.offer_id')
            ->join('products', 'offer_products.product_id', '=', 'products.id')
            ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->leftJoin('order_items', function($join) {
                $join->on('order_items.offer_id', '=', 'offers.id')
                     ->where('order_items.offer_id', '!=', null);
            })
            ->where('offers.is_active', true)
            ->whereBetween('offers.start_date', [
                Carbon::parse($this->date_from)->startOfDay(),
                Carbon::parse($this->date_to)->endOfDay()
            ])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_items_sold')
            ->get()
            ->map(function ($item) {
                return [
                    'category_id' => $item->category_id,
                    'category_name' => $item->category_name, // Nombre consistente
                    'offers_count' => $item->offers_count,
                    'products_count' => $item->products_count,
                    'avg_discount' => $item->avg_discount,
                    'total_items_sold' => $item->total_items_sold
                ];
            })
            ->toArray();
    
        $this->offersByCategoryData = $data;
        
        // Calcular métricas destacadas con la estructura corregida
        $this->mostDiscountedCategory = [
            'category_name' => collect($data)->sortByDesc('offers_count')->first()['category_name'] ?? 'N/A',
            'offers_count' => collect($data)->sortByDesc('offers_count')->first()['offers_count'] ?? 0
        ];
    
        $highestDiscount = collect($data)->sortByDesc('avg_discount')->first();
        $this->highestAvgDiscount = [
            'category_name' => $highestDiscount['category_name'] ?? 'N/A',
            'avg_discount' => $highestDiscount['avg_discount'] ?? 0
        ];
    
        $mostProducts = collect($data)->sortByDesc('products_count')->first();
        $this->mostDiscountedProductsCategory = [
            'category_name' => $mostProducts['category_name'] ?? 'N/A',
            'products_count' => $mostProducts['products_count'] ?? 0
        ];
        
        $this->dispatch('offersByCategoryChartUpdated', data: $this->offersByCategoryData);
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
            ->where('orders.status', OrderStatus::Completado)
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
            ->where('orders.status', OrderStatus::Completado)
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
            ->where('orders.status', OrderStatus::Completado)
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
        // Primero creamos un rango completo de fechas entre date_from y date_to
        $startDate = Carbon::parse($this->date_from);
        $endDate = Carbon::parse($this->date_to);

        // Obtenemos los datos agrupados por día
        $query = User::query()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->groupBy('date')
            ->orderBy('date');

        $usersData = $query->get()->keyBy('date');

        // Creamos un array con todas las fechas del rango, incluso las que no tienen usuarios
        $allDates = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $allDates[$dateString] = [
                'date' => $dateString,
                'count' => $usersData->has($dateString) ? $usersData[$dateString]->count : 0
            ];
            $currentDate->addDay();
        }

        $this->newUsersData = array_values($allDates);
        $this->dispatch('newUsersChartUpdated', data: $this->newUsersData);
    }

    public function loadOffersPerformanceData()
    {
        $query = Offer::query()
            ->select([
                'offers.id',
                'offers.name',
                'offers.discount_percentage',
                DB::raw('COUNT(order_items.id) as total_items_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('AVG(offers.discount_percentage) as avg_discount')
            ])
            ->leftJoin('offer_products', 'offers.id', '=', 'offer_products.offer_id')
            ->leftJoin('variants', 'offer_products.product_id', '=', 'variants.product_id')
            ->leftJoin('order_items', function ($join) {
                $join->on('order_items.variant_id', '=', 'variants.id')
                    ->where('order_items.offer_id', '=', DB::raw('offers.id'));
            })
            ->where('offers.is_active', true)
            ->whereBetween('offers.start_date', [
                Carbon::parse($this->date_from)->startOfDay(),
                Carbon::parse($this->date_to)->endOfDay()
            ])
            ->groupBy('offers.id', 'offers.name', 'offers.discount_percentage')
            ->orderByDesc('total_items_sold');

        $this->offersPerformanceData = $query->get()->toArray();
        $this->dispatch('offersChartUpdated', data: $this->offersPerformanceData);
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
