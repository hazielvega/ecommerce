<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class EditReceiverForm extends Form
{
    public $id;
    public $user_id = null;
    public $session_id = null;
    public $name = '';
    public $last_name = '';
    public $document_number = '';
    public $email = '';
    public $phone = '';
    public $default = false;

    // Validaciones
    public function rules()
    {
        return [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'document_number' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ];
    }

    // Mensajes de validacion personalizados
    public function validationAttributes()
    {
        return [
            'name' => 'nombre',
            'last_name' => 'apellidos',
            'document_number' => 'número de documento',
            'email' => 'email',
            'phone' => 'telefono',
        ];
    }

    // Metodo para cargar la información del receptor a editar
    public function edit($receiver)
    {
        $this->id = $receiver->id;
        $this->user_id = $receiver->user_id;
        $this->session_id = $receiver->session_id;
        $this->name = $receiver->name;
        $this->last_name = $receiver->last_name;
        $this->document_number = $receiver->document_number;
        $this->email = $receiver->email;
        $this->phone = $receiver->phone;
        $this->default = $address->default;
    }

    // Metodo para actualizar el receptor
    public function update()
    {
        $this->validate();

        $receiver = Receiver::find($this->id);
        $receiver->update([
            'user_id' => $this->user_id,
            'session_id' => $this->session_id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'document_number' => $this->document_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'default' => $this->default
        ]);

        $this->reset();
    }

}
