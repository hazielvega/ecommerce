<?php

namespace App\Livewire\Admin\Products;

use App\Exports\ProductsExport;
use App\Exports\VariantsExport;
use App\Models\Category;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ProductExcel extends Component
{
    public $filter = 'all'; // Filtro por defecto
    public $open = false;
    public $categories;

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function exportProducts()
    {
        return Excel::download(new ProductsExport($this->filter), 'productos.xlsx');
    }

    public function exportVariants()
    {
        return Excel::download(new VariantsExport($this->filter), 'variantes.xlsx');
    }
    
    public function render()
    {
        return view('livewire.admin.products.product-excel');
    }
}
