<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Navigation extends Component
{
    public $categories;
    public $search;

    protected $listeners = ['cartUpdated' => '$refresh'];

    public function mount()
    {
        $this->categories = Category::with('subcategories')->get();
    }

    #[On('search')]
    public function updateSearch($search)
    {
        $this->search = $search;
    }

    public function searchProduct()
    {
        return redirect()->route('products.search', ['search' => $this->search]);
    }

    #[Computed]
    public function filteredProducts()
    {
        return Product::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->select('id', 'name', 'image_path', 'sale_price')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}
