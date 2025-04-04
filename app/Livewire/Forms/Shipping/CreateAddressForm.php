<?php

namespace App\Livewire\Forms\Shipping;

use App\Enums\TypeOfDocuments;
use App\Models\Address;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateAddressForm extends Form
{
    public $user_id = null;
    public $session_id = null;
    public $calle = '';
    public $numero = '';
    public $ciudad = '';
    public $provincia = '';
    public $codigo_postal = '';
    public $description = '';
    public $type = '';
    public $is_shipping = false;
    public $is_billing = false;

    // Inicializa el formulario
    public function mount()
    {
        if (auth()->check()) {
            $this->user_id = auth()->id();
        } else {
            $this->session_id = session()->getId();
        }
    }

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

    // Mensajes de validación personalizados
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

    // Método para guardar la dirección de envío
    public function save()
    {
        $this->validate();

        // Determina si la dirección debe ser predeterminada para envío
        $is_shipping = false;
        if (auth()->check()) {
            $is_shipping = auth()->user()->addresses()->count() === 0;
        } else {
            $is_shipping = Address::whereNull('user_id')
                ->where('session_id', $this->session_id)
                ->count() === 0;
        }

        // Determina si la dirección debe ser predeterminada para facturación
        $is_billing = false;
        if (auth()->check()) {
            $is_billing = auth()->user()->addresses()->count() === 0;
        } else {
            $is_billing = Address::whereNull('user_id')
                ->where('session_id', $this->session_id)
                ->count() === 0;
        }

        // Si es predeterminada para envío, elimina la predeterminación de otras direcciones
        if ($is_shipping) {
            Address::where(function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', $this->session_id);
                }
            })->update(['is_shipping' => false]);
        }

        // Si es predeterminada para facturación, elimina la predeterminación de otras direcciones
        if ($is_billing) {
            Address::where(function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', $this->session_id);
                }
            })->update(['is_billing' => false]);
        }

        // Crea la dirección
        $address = new Address();
        $address->user_id = auth()->id() ?? null;
        $address->session_id = auth()->check() ? null : $this->session_id;
        $address->calle = $this->calle;
        $address->numero = $this->numero;
        $address->ciudad = $this->ciudad;
        $address->provincia = $this->provincia;
        $address->codigo_postal = $this->codigo_postal;
        $address->description = $this->description;
        $address->type = $this->type;
        $address->is_shipping = $is_shipping;
        $address->is_billing = $is_billing;
        $address->save();


        // Resetea el formulario
        $this->resetForm();
    }


    // Método para resetear el formulario
    public function resetForm()
    {
        $this->reset(['calle', 'numero', 'ciudad', 'provincia', 'codigo_postal', 'description', 'type', 'is_shipping', 'is_billing']);
    }
}
