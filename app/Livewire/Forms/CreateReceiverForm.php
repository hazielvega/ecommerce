<?php

namespace App\Livewire\Forms;

use App\Models\Receiver;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateReceiverForm extends Form
{
    public $user_id = null;
    public $session_id = null;
    public $name = '';
    public $last_name = '';
    public $document_number = '';
    public $email = '';
    public $phone = '';
    public $default = false;

    public function mount()
    {
        if (auth()->check()) {
            $this->user_id = auth()->id();
        }else{
            $this->session_id = session()->getId();
        }
    }

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

    // Metodo para guardar nuevo receptor
    public function save()
    {
        $this->validate();

        // Determina si el receptor debe ser predeterminado
        $default = false;
        if (auth()->check()) {
            $default = auth()->user()->receivers()->count() === 0;
        } else {
            $default = Receiver::whereNull('user_id')
                ->where('session_id', $this->session_id)
                ->count() === 0;
        }

        // Si es predeterminada, elimina la predeterminación de otras direcciones
        if ($default) {
            Receiver::where(function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', $this->session_id);
                }
            })->update(['default' => false]);
        }
        
        // Crea al nuevo receptor
        $receiver = new Receiver();
        $receiver->user_id = $this->user_id;
        $receiver->session_id = $this->session_id;
        $receiver->name = $this->name;
        $receiver->last_name = $this->last_name;
        $receiver->document_number = $this->document_number;
        $receiver->email = $this->email;
        $receiver->phone = $this->phone;
        $receiver->default = $default;
        $receiver->save();

        // Resetea el formulario
        $this->resetForm();
    }

    // Resetea el formulario
    public function resetForm()
    {
        $this->reset(['name', 'last_name', 'document_number', 'email', 'phone', 'default']);
    }
}
