<?php

namespace App\Livewire\Admin;

use App\Exports\SalesExport;
use App\Models\Category;
use App\Models\Order;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class Sales extends Component
{
    public $categories = [];
    public $selected_category = "";
    public $selected_subcategory = "";
    public $date_from = "";
    public $date_to = "";

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function getSubcategoriesProperty()
    {
        return $this->selected_category
            ? Category::find($this->selected_category)?->subcategories ?? collect([])
            : collect([]);
    }

    public function generateReport()
    {
        $fileName = 'reporte_ventas_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new SalesExport(
            $this->selected_category,
            $this->selected_subcategory,
            $this->date_from,
            $this->date_to
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.admin.sales');
    }
}
