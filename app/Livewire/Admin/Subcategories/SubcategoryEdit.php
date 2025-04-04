<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SubcategoryEdit extends Component
{
    public $subcategory;

    public $categories;

    public $subcategoryEdit;

    public function mount($subcategory)
    {
        $this->categories = Category::all();
        $this->subcategoryEdit = [
            'category_id' => $subcategory->category_id,
            'name' => $subcategory->name,
        ];
    }

    public function save()
    {
        $this->validate([
            'subcategoryEdit.category_id' => 'required|exists:categories,id',
            'subcategoryEdit.name' => 'required',
        ],[],[
            'subcategoryEdit.category_id' => 'categoría',
            'subcategoryEdit.name' => 'nombre',
        ]);

        $this->subcategory->update($this->subcategoryEdit);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Subcategoría actualizada correctamente',
        ]);

        return redirect()->route('admin.subcategories.index');
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-edit');
    }
}
