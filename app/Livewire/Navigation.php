<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Navigation extends Component
{
    // Recupero las categorias
    public $categories;

    // Texto ingresado en el buscador para filtrar productos
    public $search;

    public function mount()
    {
        $this->categories = Category::all();
    }

    // Escucha el evento "search" y actualiza la propiedad $search con el texto del buscador
    #[On('search')]
    public function search($search)
    {
        $this->search = $search; // Almacena el texto ingresado en el buscador
    }

    // Metodo para realizar una busqueda de productos
    public function searchProduct()
    {
        //Redirecciona a la busqueda de productos
        return redirect()->route('products.search', ['search' => $this->search]);    
    }

    public function render()
    {
        // Recupera los productos aplicando los filtros seleccionados
        $products = Product::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%'); // Filtra productos por nombre
        })->take(5)->get();

        // Devuelve la vista del componente junto con los productos filtrados
        return view('livewire.navigation', compact('products'));
    }
}
