<?php

namespace App\Livewire;

use App\Enums\TypeOfDocuments;
use App\Livewire\Forms\CreateReceiverForm;
use App\Livewire\Forms\EditReceiverForm;
use App\Livewire\Forms\Shipping\CreateAddressForm;
use App\Livewire\Forms\Shipping\EditAddressForm;
use App\Models\Address;
use App\Models\Receiver;
use Livewire\Component;
use Illuminate\Support\Str;

class ShippingAddresses extends Component
{
    // Almacena todas las direcciones del usuario o invitado
    public $addresses;

    // Almacena todos los receptores del usuario o invitado
    public $receivers;

    // Almacena una nueva dirección. Abre o cierra el formulario de creación de una nueva dirección
    public $newAddress = false;
    // Instanciamos el form object para crear una nueva dirección
    public CreateAddressForm $createAddress;
    // Instanciamos el form object para editar una dirección
    public EditAddressForm $editAddress;
    public $edit_address = false;

    // Almacena un nuevo receptor. Abre o cierra el formulario de creación de un nuevo receptor
    public $newReceiver = false;
    // // Instanciamos el form object para crear un nuevo receptor
    public CreateReceiverForm $createReceiver;
    // // Instanciamos el form object para editar un receptor
    public EditReceiverForm $editReceiver;


    public function mount()
    {
        // Recuperamos los receptores de usuario autenticado o de invitado
        $this->recoverReceivers();
        // Recuperamos direcciones de usuario autenticado o de invitado
        $this->recoverAddresses();
    }

    public function recoverReceivers()
    {
        if (auth()->check()) {
            $this->receivers = Receiver::where('user_id', auth()->id())->get();
        } else {
            $this->receivers = Receiver::where('session_id', session()->getId())->get();
        }

        if ($this->receivers->count() == 0) {
            $this->newReceiver = true;
        } else {
            $this->newReceiver = false;
        }
    }

    /**
     * Método para recuperar las direcciones del usuario autenticado o del usuario invitado
     */
    public function recoverAddresses()
    {
        if (auth()->check()) {
            // Recupera todas las direcciones del usuario autenticado
            $this->addresses = Address::where('user_id', auth()->id())->get();
        } else {
            // Recupera todas las direcciones del usuario invitado usando session_id
            $this->addresses = Address::where('session_id', session()->getId())->get();
        }

        if ($this->addresses->count() == 0) {
            $this->newAddress = true;
        } else {
            $this->newAddress = false;
        }
    }


    // Metodo para guardar un nuevo receptor
    public function storeReceiver()
    {
        $this->createReceiver->session_id = session()->getId(); // Se pasa session_id para invitados
        if (auth()->check()) {
            $this->createReceiver->user_id = auth()->id();
        }
        // dd($this->createReceiver);
        $this->createReceiver->save();
        $this->recoverReceivers();
        // Cierra el formulario
        $this->newReceiver = false;
    }

    /**
     * Método para guardar una nueva dirección
     */
    public function storeAddress()
    {
        // Guardo la nueva dirección, llamando al método save() del form object
        $this->createAddress->session_id = session()->getId(); // Se pasa session_id para invitados
        if (auth()->check()) {
            $this->createAddress->user_id = auth()->id();
        }

        // dd($this->createAddress->session_id);
        $this->createAddress->save();

        // Actualiza la lista de direcciones
        $this->recoverAddresses();

        // Cierra el formulario
        $this->newAddress = false;
    }

    // Metodo para cargar datos en el formulario de edicion de un receptor
    public function editReceiver($id)
    {
        // Busca el receptor pasada por parámetro
        $receiver = Receiver::find($id);

        // Ejecuta el método edit() del form object EditReceiverForm
        $this->editReceiver->edit($receiver);
    }

    /**
     * Método para cargar datos en el formulario de edición de una dirección
     */
    public function editAddress($id)
    {
        // Busca la dirección pasada por parámetro
        $address = Address::find($id);

        // Ejecuta el método edit() del form object EditAddressForm
        $this->editAddress->edit($address);
    }

    // Metodo para actualizar un receptor
    public function updateReceiver()
    {
        // Ejecuta el método update() del form object EditReceiverForm
        $this->editReceiver->update();

        // Actualiza la lista de receptores
        $this->recoverReceivers();
    }

    /**
     * Método para actualizar una dirección
     */
    public function updateAddress()
    {
        // Ejecuta el método update() del form object EditAddressForm
        $this->editAddress->update();

        // Actualiza la lista de direcciones
        $this->recoverAddresses();
    }

    // Metodo para borrar un receptor
    public function deleteReceiver($id)
    {
        // Busca el receptor y lo elimina
        $receiver = Receiver::find($id);
        $receiver->delete();

        // Recupera los receptores restantes
        $this->recoverReceivers();

        // Si el receptor eliminado era el predeterminado, asignar uno nuevo por defecto
        if ($this->receivers->where('default', true)->count() == 0 && $this->receivers->count() > 0) {
            $this->receivers->first()->update(['default' => true]);
        }
    }

    /**
     * Método para borrar una dirección
     */
    public function deleteAddress($id)
    {
        // Busca la dirección y la elimina
        $address = Address::find($id);
        $address->delete();

        // Recupera las direcciones restantes
        $this->recoverAddresses();

        // Si la dirección eliminada era la predeterminada para envío, asignar una nueva por defecto
        if ($this->addresses->where('is_shipping', true)->count() == 0 && $this->addresses->count() > 0) {
            $this->addresses->first()->update(['is_shipping' => true]);
        }

        // Si la direccion eliminada era la predeterminada para facturacion, asignar una nueva por defecto
        if ($this->addresses->where('is_billing', true)->count() == 0 && $this->addresses->count() > 0) {
            $this->addresses->first()->update(['is_billing' => true]);
        }
    }

    // Metodo para cambiar el receptor predeterminado
    public function setDefaultReceiver($id)
    {
        // Itera sobre los receptores y establece el receptor seleccionado como predeterminado
        $this->receivers->each(function ($receiver) use ($id) {
            $receiver->update([
                'default' => $receiver->id == $id,
            ]);
        });
    }

    /**
     * Método para cambiar la dirección predeterminada de envío
     */
    public function setDefaultShippingAddress($id)
    {
        // Itera sobre las direcciones y establece la dirección seleccionada como predeterminada
        $this->addresses->each(function ($address) use ($id) {
            $address->update([
                'is_shipping' => $address->id == $id,
            ]);
        });
    }

    /**
     * Metodo para cambiar la direccion predeterminada de facturacion
     */
    public function setDefaultBillingAddress($id)
    {
        // Itera sobre las direcciones y establece la direccion seleccionada como predeterminada
        $this->addresses->each(function ($address) use ($id) {
            $address->update([
                'is_billing' => $address->id == $id,
            ]);
        });
    }

    public function validateBeforeCheckout()
    {
        // Verifica si hay direcciones y destinatarios disponibles
        if ($this->addresses->isEmpty() || $this->receivers->isEmpty()) {
            $message = "No puedes continuar con la compra debido a los siguientes problemas:\n";

            if ($this->addresses->isEmpty()) {
                $message .= "- No has seleccionado una dirección de envío y/o facturación.\n";
            }

            if ($this->receivers->isEmpty()) {
                $message .= "- No has seleccionado un destinatario.\n";
            }

            // Emitir evento para mostrar alerta en la vista
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Atención',
                'text' => $message,
            ]);

            return;
        }

        // Si la validación es exitosa, redirigir al checkout
        return redirect()->route('checkout.index');
    }


    public function render()
    {
        return view('livewire.shipping-addresses');
    }
}
