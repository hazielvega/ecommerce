<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{
    public $open = false;
    public $name = '';

    // Metodo para guardar una nueva categoria
    public function createCategory()
    {
        $this->validate([
            'name' => 'required',
        ]);

        Category::create([
            'name' => $this->name,
        ]);

        $this->name = '';

        // $this->dispatch('categoryUpdated'); // Emite el evento

        // Alerta
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'Categoria agregada correctamente',
        ]);

        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.categories.create-category');
    }
}
