<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Carbon;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $statusFilter = 'all';
    public $categoryFilter = '';
    public $subcategoryFilter = '';
    public $subcategories = [];
    public $perPage = 10;
    public $stockAlertOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'statusFilter' => ['except' => 'all'],
        'categoryFilter' => ['except' => ''],
        'subcategoryFilter' => ['except' => ''],
        'stockAlertOnly' => ['except' => false],
        'perPage' => ['except' => 10]
    ];

    public function updatedCategoryFilter($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->subcategoryFilter = '';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingSubcategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStockAlertOnly()
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
        $this->categoryFilter = '';
        $this->subcategoryFilter = '';
        $this->stockAlertOnly = false;
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->with(['subcategory.category', 'variants'])
            ->withCount('variants')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%')
                      ->orWhere('id', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('is_enabled', $this->statusFilter === 'active');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->whereHas('subcategory', function ($q) {
                    $q->where('category_id', $this->categoryFilter);
                });
            })
            ->when($this->subcategoryFilter, function ($query) {
                $query->where('subcategory_id', $this->subcategoryFilter);
            })
            ->when($this->stockAlertOnly, function ($query) {
                $query->whereHas('variants', function ($q) {
                    $q->whereColumn('stock', '<=', 'min_stock');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $categories = Category::all();
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_enabled', true)->count();
        $outOfStockProducts = Product::whereHas('variants', function($q) {
            $q->where('stock', 0);
        })->count();

        return view('livewire.admin.products.product-index', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'outOfStockProducts' => $outOfStockProducts,
        ]);
    }
}