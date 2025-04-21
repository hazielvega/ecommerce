<?php

namespace App\Livewire\Admin\Products;

use App\Exports\ProductsExport;
use App\Exports\VariantsExport;
use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ProductExcel extends Component
{
    public $filterProducts = 'all';
    public $filterVariants = 'all';
    public $selectedCategory = '';
    public $selectedSubcategory = '';
    public $subcategories = [];
    public $stockAlertOnly = false;

    public function mount()
    {
        $this->subcategories = Subcategory::all();
    }

    public function updatedSelectedCategory($value)
    {
        $this->subcategories = $value
            ? Category::find($value)->subcategories
            : Subcategory::all();
        $this->selectedSubcategory = '';
    }

    public function exportProducts()
    {
        $filter = $this->stockAlertOnly ? 'low_stock' : $this->filterProducts;
        if ($this->selectedCategory) {
            $filter = $this->selectedCategory;
            if ($this->stockAlertOnly) {
                $filter .= '_low_stock';
            }
        }

        return Excel::download(
            new ProductsExport($filter, $this->selectedSubcategory),
            'productos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportVariants()
    {
        $filter = $this->stockAlertOnly ? 'low_stock' : $this->filterVariants;
        if ($this->selectedCategory) {
            $filter = $this->selectedCategory;
            if ($this->stockAlertOnly) {
                $filter .= '_low_stock';
            }
        }

        return Excel::download(
            new VariantsExport($filter, $this->selectedSubcategory),
            'variantes_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.products.product-excel', compact('categories'));
    }
}
