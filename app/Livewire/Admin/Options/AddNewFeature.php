<?php

namespace App\Livewire\Admin\Options;

use Livewire\Component;

class AddNewFeature extends Component
{
    // Recupero la informacion de la opcion a la que quiero añadir un feauture
    public $option;

    // Array para las nuevas caracteristicas
    public $newFeature = [
        'value' => '',
        'description' => ''
    ];

    // Metodo para anadir una nueva caracteristica a una opcion ya creada
    public function addFeature()
    {
        $this->validate([
            'newFeature.value' => 'required',
            'newFeature.description' => 'required'
        ]);

        // Creo el nuevo feature
        $this->option->features()->create($this->newFeature);

        // Actualizamos la lista de features llamando al metodo update
        $this->dispatch('featureAdded');

        // Reseteo el atributo option
        $this->reset('newFeature');
    }

    public function render()
    {
        return view('livewire.admin.options.add-new-feature');
    }
}
