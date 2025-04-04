<?php

namespace App\Livewire\Forms\Admin\Options;

use App\Models\Option;
use Livewire\Attributes\Validate;
use Livewire\Form;

class NewOptionForm extends Form
{
    public $name;
    public $type = 1;
    public $features = [
        [
            'value' => '',
            'description' => '',
        ]
    ];

    // Cuando es false, el modal está cerrado. Lo sincronizamos con la vista a traves del metodo wire.live
    public $openModal = false;

    // Validacion
    public function rules()
    {
        // Reglas de validacion
        $rules = [
            'name' => 'required',
            'type' => 'required|in:1,2',
            'features' => 'required|array|min:1',
        ];

        // Ciclo para validar los input dependiendo de lo que vayamos seleccionando
        foreach ($this->features as $index => $feature) {

            if ($this->type == 1) {
                # Texto
                $rules["features.{$index}.value"] = 'required';
            } else {
                # Color
                $rules["features.{$index}.value"] = 'required|starts_with:#';
            }

            $rules["features.{$index}.description"] = 'required';
        }

        return $rules;
    }

    // Metodo para traducir las validaciones en caso de error
    public function validationAttributes()
    {
        $attributes = [
            'name' => 'nombre',
            'type' => 'tipo',
            'features' => 'características',
        ];

        foreach ($this->features as $index => $feature) {
            $attributes["features.{$index}.value"] = 'valor '. ($index + 1);
            $attributes["features.{$index}.description"] = 'característica '. ($index + 1);
        }

        return $attributes;
    }

    // Utilizamos este metodo para añadir features al form object de la nueva opción
    public function addFeature()
    {
        $this->features[] = [
            'value' => '',
            'description' => ''
        ];
    }


    // Utilizamos este metodo para quitar features del form object de la nueva opción
    public function removeFeature($index)
    {
        unset($this->features[$index]);

        $this->features = array_values($this->features);
    }

    // Metodo para crear una opcion
    public function save()
    {
        $this->validate();
        // Creamos una opcion nueva
        $option = Option::create([
            'name' => $this->name,
            'type' => $this->type,
        ]);

        // Agregamos las features
        foreach ($this->features as $feature) {
            $option->features()->create([
                'value' => $feature['value'],
                'description' => $feature['description'],
            ]);
        }

        // Reseteamos los atributos
        $this->reset();
    }
}
