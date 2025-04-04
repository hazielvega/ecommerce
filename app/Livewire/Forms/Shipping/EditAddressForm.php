<?php

namespace App\Livewire\Forms\Shipping;

use App\Enums\TypeOfDocuments;
use App\Models\Address;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditAddressForm extends Form
{
    public $id;
    public $user_id = null;
    public $session_id = null;
    public $calle = '';
    public $numero = '';
    public $ciudad = '';
    public $provincia = '';
    public $codigo_postal = '';
    public $description = '';
    public $type = '';
    public $default = false;

    // Validaciones
    public function rules()
    {
        return [
            'calle' => 'required|string',
            'numero' => 'required|string',
            'ciudad' => 'required|string',
            'provincia' => 'required|string',
            'codigo_postal' => 'required|string',
            'description' => 'string',
            'type' => 'required|in:1,2',
        ];
    }

    public function validationAttributes()
    {
        return [
            'calle' => 'calle',
            'numero' => 'número',
            'ciudad' => 'ciudad',
            'provincia' => 'provincia',
            'codigo_postal' => 'código postal',
            'description' => 'descripción',
            'type' => 'domicilio o sucursal',
        ];
    }

    // Método para cargar la información de la dirección a editar
    public function edit($address)
    {
        $this->id = $address->id;
        $this->user_id = $address->user_id;
        $this->session_id = $address->session_id;
        $this->calle = $address->calle;
        $this->numero = $address->numero;
        $this->ciudad = $address->ciudad;
        $this->provincia = $address->provincia;
        $this->codigo_postal = $address->codigo_postal;
        $this->description = $address->description;
        $this->type = $address->type;
        $this->default = $address->default;
    }

    // Método para actualizar la dirección
    public function update()
    {
        $this->validate();

        $address = Address::find($this->id);
        $address->update([
            'user_id' => $this->user_id,
            'session_id' => $this->session_id,
            'calle' => $this->calle,
            'numero' => $this->numero,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'codigo_postal' => $this->codigo_postal,
            'description' => $this->description,
            'type' => $this->type,
            'default' => $this->default
        ]);

        $this->reset();
    }
}
