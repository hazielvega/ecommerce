<?php

namespace App\Livewire\Admin\Options;

use App\Livewire\Forms\Admin\Options\NewOptionForm;
use App\Models\Feature;
use App\Models\Option;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageOptions extends Component
{
    // recupero todas las opciones de la base de datos
    public $options;

    // Se va a utilizar para controlar la cantidad de valores que se va agregar en la opcion nueva
    // Utilizo una instancia de un Form Object(livewire 3) que cuenta con los atributos para la creacion
    public NewOptionForm $newOption;

    // el metodo mount se ejecuta cuando se inicializa el componente 
    public function mount()
    {
        $this->options = Option::with('features')->get();
    }

    // Utilizamos este metodo para añadir features al array de la nueva opción
    public function addFeature()
    {
        $this->newOption->addFeature();
    }

    // Utilizamos este metodo para quitar features del form object de la nueva opción
    public function removeFeature($index)
    {
        $this->newOption->removeFeature($index);
    }

    // Utilizo este metodo para crear una opcion nueva
    public function addOption()
    {
        // Llamamos al metodo del form object para la creacion de una opcion
        $this->newOption->save();

        // Volvemos a hacer la consulta del metodo mount para refrescar la vista de opciones con la nueva actualizacion
        $this->options = Option::with('features')->get();
    }

    // Utilizo este metodo para actualizar la lista de opciones mientras vaya agregando características
    // Agregamos un oyente para el evento featureAdded enviado desde el AddNewFeature en el metodo addFeature
    #[On('featureAdded')]
    public function updateOptionList()
    {
        // Volvemos a hacer la consulta del metodo mount para refrescar la vista de opciones con la nueva actualizacion
        $this->options = Option::with('features')->get();
    }

    // Utilizo este metodo para eliminar una caracteristica asociada a una opcion existente
    public function removeAddedFeature(Feature $feature)
    {
        $feature->delete();

        // Volvemos a hacer la consulta del metodo mount para refrescar la vista de opciones con la nueva actualizacion
        $this->options = Option::with('features')->get();
    }

    // Utilizo este metodo para eliminar una opcion
    public function deleteOption(Option $option)
    {
        $option->delete();

        // Volvemos a hacer la consulta del metodo mount para refrescar la vista de opciones con la nueva actualizacion
        $this->options = Option::with('features')->get();
    }


    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
