<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;

class EditCategory extends Component
{
    public $open = false;
    public $category;
    public $name;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
    }

    // Método para actualizar la categoría
    public function updateCategory()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $this->category->update([
            'name' => $this->name,
        ]);

        // $this->dispatch('categoryUpdated'); // Emite el evento para actualizar la tabla

        // Alerta
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Categoria actualizada correctamente',
        ]);

        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.categories.edit-category');
    }
}
