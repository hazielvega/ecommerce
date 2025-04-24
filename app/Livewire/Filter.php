<?php

namespace App\Livewire;

use App\Models\Feature;
use App\Models\Option;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Filter extends Component
{
    use WithPagination;

    // ID de la categoría seleccionada para filtrar los productos
    public $category_id;
    // ID de la subcategoría seleccionada para filtrar los productos
    public $subcategory_id;
    // ID de las ofertas activas
    public $active_offers;
    // Opciones relacionadas con los productos de la familia seleccionada (para filtros)
    public $options;
    // Parámetro para ordenar el listado de productos
    public $orderBy = 1;
    // Texto ingresado en el buscador para filtrar productos
    public $search;
    public $s;

    public $selected_features = []; // IDs de las características seleccionadas (e.g., talles, colores)

    public $grouped_features = [];

    public $combinations = [];

    // public $products;

    public $consulta;
    public $consultaSql;
    public $consultaBindings;


    public function mount()
    {
        // Inicializo la busqueda
        $this->search = $this->s;
        // Inicialización de las opciones relacionadas con los productos
        if (empty($this->active_offers)) {
            $this->options = Option::verifyCategory($this->category_id)
                ->verifySubcategory($this->subcategory_id)
                ->verifySearch($this->search)
                ->get()->toArray(); // Convierte las opciones en un array para su uso en la vista
        } else {
            $this->options = Option::inOffers($this->active_offers)->get()->toArray();
        }



        $this->applyFilters();
    }

    // Escucha el evento "search" y actualiza la propiedad $search con el texto del buscador
    #[On('search')]
    public function search($search)
    {
        $this->search = $search; // Almacena el texto ingresado en el buscador
    }

    // Metodo para agrupar las características dependiendo de la opcion a la que pertenecen
    private function groupFeaturesByOption()
    {
        $grouped = []; // Inicializamos el array para agrupar las características

        foreach ($this->selected_features as $featureId) {
            // Recuperamos la característica por su ID
            $feature = Feature::find($featureId);

            if ($feature) {
                // Agrupamos por el option_id de la característica
                $grouped[$feature->option_id][] = $featureId;
            }
        }

        return $grouped; // Devuelve un array agrupado por option_id
    }


    // Metodo para generar las combinaciones
    private function generateCombinations($groupedFeatures)
    {
        // Si solo hay un grupo, devuelve sus elementos directamente
        if (count($groupedFeatures) === 1) {
            return $this->grouped_features;
        } elseif (count($groupedFeatures) === 0) {
            return [];
        }

        // Convertimos los valores del array asociativo en un array indexado
        $arrays = array_values($groupedFeatures);

        // Generamos las combinaciones recursivamente
        return $this->combine($arrays);
    }

    // Método recursivo para generar combinaciones
    private function combine($arrays, $prefix = [])
    {
        if (empty($arrays)) {
            return [$prefix];
        }

        $result = [];
        $firstArray = array_shift($arrays);

        foreach ($firstArray as $value) {
            $result = array_merge($result, $this->combine($arrays, array_merge($prefix, [$value])));
        }

        return $result;
    }

    public function applyFilters()
    {
        // Guardamos los filtros seleccionados para usarlos en la consulta de render()
        $this->grouped_features = $this->groupFeaturesByOption();
        $this->combinations = $this->generateCombinations($this->grouped_features);

        // Reseteamos la paginación al aplicar nuevos filtros
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->selected_features = [];
        $this->grouped_features = [];
        $this->combinations = [];
        $this->resetPage();
    }


    public function render()
    {
        if (empty($this->active_offers)) {        // Obtener productos base filtrados por categoría y subcategoría
            $query = Product::verifyCategory($this->category_id)
                ->verifySubcategory($this->subcategory_id)
                ->verifySearch($this->search)
                ->where('is_enabled', true)
                ->customOrder($this->orderBy);

            if (!empty($this->combinations)) {
                $query->whereHas('variants', function ($variantQuery) {
                    if (count($this->combinations) === 1 && count($this->grouped_features) === 1) {
                        $combination = array_shift($this->combinations);
                        $variantQuery->whereHas('features', function ($query) use ($combination) {
                            $query->whereIn('features.id', $combination);
                        });
                    } else {
                        $variantQuery->where(function ($featureQuery) {
                            foreach ($this->combinations as $combination) {
                                $featureQuery->orWhere(function ($query) use ($combination) {
                                    foreach ($combination as $featureId) {
                                        $query->whereHas('features', function ($subQuery) use ($featureId) {
                                            $subQuery->where('features.id', $featureId);
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        } else {
            $query = Product::inOffers($this->active_offers)
                ->where('is_enabled', true)
                ->customOrder($this->orderBy);
        }


        // Retornamos la vista con la consulta paginada
        return view('livewire.filter', [
            'products' => $query->paginate(8), // Pagina los productos correctamente
        ]);
    }
}
