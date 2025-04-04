<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\Subcategory;
use App\Models\User;
use Livewire\Component;

class Reports extends Component
{
    // Variables para los gráficos
    public $monthlyRevenueLabels = [];
    public $monthlyRevenueValues = [];

    public $topProductsLabels = [];
    public $topProductsValues = [];

    public $topSubcategoriesLabels = [];
    public $topSubcategoriesValues = [];
    public $topSubcategoriesCombined = [];

    public $newCustomersLabels = [];
    public $newCustomersValues = [];

    public $categoriesLabels = [];
    public $categoriesValues = [];

    public function mount()
    {
        $this->loadMonthlyRevenue();
        $this->loadTopProducts();
        $this->loadTopSubcategories();
        $this->loadNewCustomersGrowth();
        $this->loadCategorySales();
    }

    // Ingresos mensuales
    public function loadMonthlyRevenue()
    {
        $data = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $this->monthlyRevenueLabels = $data->pluck('month')->toArray();
        $this->monthlyRevenueValues = $data->pluck('total')->toArray();
    }

    // Método para cargar productos más vendidos
    public function loadTopProducts()
    {
        $data = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('variants', 'order_items.variant_id', '=', 'variants.id')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->selectRaw('products.name as product, SUM(order_items.quantity) as total_sold')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $this->topProductsLabels = $data->pluck('product')->toArray();
        $this->topProductsValues = $data->pluck('total_sold')->toArray();
    }


    // Subcategorías más vendidas
    public function loadTopSubcategories()
    {
        $data = Subcategory::join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('products', 'subcategories.id', '=', 'products.subcategory_id')
            ->join('variants', 'products.id', '=', 'variants.product_id')
            ->join('order_items', 'variants.id', '=', 'order_items.variant_id')
            ->selectRaw('subcategories.name as subcategory, categories.name as category, SUM(order_items.quantity) as total_sold')
            ->groupBy('subcategories.name', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $this->topSubcategoriesCombined = $data->map(fn($item) => "{$item->subcategory} ({$item->category})")->toArray();
        $this->topSubcategoriesValues = $data->pluck('total_sold')->toArray();
    }

    // Nuevos clientes
    public function loadNewCustomersGrowth()
    {
        $data = User::selectRaw('DATE(created_at) as registration_date, COUNT(*) as new_customers')
            ->groupBy('registration_date')
            ->orderBy('registration_date')
            ->get();

        $this->newCustomersLabels = $data->pluck('registration_date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M Y'))->toArray();
        $this->newCustomersValues = $data->pluck('new_customers')->toArray();
    }

    // Ventas por categoría (nuevo gráfico circular)
    public function loadCategorySales()
    {
        $data = Category::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
            ->join('products', 'subcategories.id', '=', 'products.subcategory_id')
            ->join('variants', 'products.id', '=', 'variants.product_id')
            ->join('order_items', 'variants.id', '=', 'order_items.variant_id')
            ->selectRaw('categories.name as category, SUM(order_items.quantity) as total_sold')
            ->groupBy('categories.name')
            ->orderByDesc('total_sold')
            ->get();

        $this->categoriesLabels = $data->pluck('category')->toArray();
        $this->categoriesValues = $data->pluck('total_sold')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.reports');
    }
}
