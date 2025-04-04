<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;

class SubcategoryCreate extends Component
{
    public $open = false;
    public $categories;
    public $subcategory = [
        'category_id' => '',
        'name' => '',
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function save()
    {
        $this->validate([
            'subcategory.category_id' => 'required|exists:categories,id',
            'subcategory.name' => 'required',
        ],[],[
            'subcategory.category_id' => 'categoría',
            'subcategory.name' => 'nombre',
        ]);

        Subcategory::create($this->subcategory);

        $this->dispatch('subcategoryUpdated'); // Emite el evento para actualizar la tabla

        $this->subcategory = ['category_id' => '', 'name' => ''];
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-create');
    }
}
