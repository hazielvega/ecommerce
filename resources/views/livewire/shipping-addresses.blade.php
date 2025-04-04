<div>
    {{-- Boton para agregar una nueva direccion --}}
    @if (!$newAddress)
        <button class="btn btn-indigo w-full flex items-center justify-center mb-4" wire:click="$set('newAddress', true)">
            Agregar nueva dirección <i class="fa-solid fa-plus ml-2"></i>
        </button>
    @endif

    {{-- Formulario para agregar una nueva direccion --}}
    @if ($newAddress)
        <section class="bg-[#c2bf92] rounded-lg shadow overflow-hidden mb-3">
            {{-- Encabezado --}}
            <header class="bg-gray-600 px-4 py-2">
                <h2 class="text-white text-lg">
                    Nueva dirección
                </h2>
            </header>

            <div class="p-4">
                {{-- Errores de validacion --}}
                <x-validation-errors class="mb-6"></x-validation-errors>

                {{-- Formulario --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

                    {{-- Tipo de direccion --}}
                    <div class="lg:col-span-1 space-y-1">
                        <label class="font-semibold">
                            Tipo
                        </label>
                        <x-select wire:model="createAddress.type">
                            <option value="">
                                Tipo de dirección
                            </option>
                            <option value="1">
                                Domicilio
                            </option>
                            <option value="2">
                                Sucursal
                            </option>
                        </x-select>
                    </div>

                    {{-- Calle --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Calle
                        </label>
                        <x-input type="text" placeholder="Calle" class="w-full" wire:model="createAddress.calle" />
                    </div>

                    {{-- Numero --}}
                    <div class="lg:col-span-1 space-y-1">
                        <label class="font-semibold">
                            Número
                        </label>
                        <x-input type="text" placeholder="Número" class="w-full" wire:model="createAddress.numero" />
                    </div>

                    {{-- Provincia --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Provincia
                        </label>
                        <x-input type="text" placeholder="Provincia" class="w-full"
                            wire:model="createAddress.provincia" />
                    </div>

                    {{-- Ciudad --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Ciudad
                        </label>
                        <x-input type="text" placeholder="Ciudad" class="w-full" wire:model="createAddress.ciudad" />
                    </div>

                    {{-- Codigo postal --}}
                    <div class="lg:col-span-1 space-y-1">
                        <label class="font-semibold">
                            Código postal
                        </label>
                        <x-input type="text" placeholder="Código postal" class="w-full"
                            wire:model="createAddress.codigo_postal" />
                    </div>

                    {{-- Descripcion de la direccion --}}
                    <div class="lg:col-span-3 space-y-1">
                        <label class="font-semibold">
                            Descripción (opcional)
                        </label>
                        <x-input type="text" placeholder="Por ejemplo: portón blanco" class="w-full"
                            wire:model="createAddress.description" />
                    </div>

                    {{-- Same address --}}
                    {{-- <div class="col-span-4 flex items-center space-x-2">
                        <input type="checkbox" id="sameAddress" wire:model.live="sameAddress"
                            class="form-checkbox h-5 w-5 text-indigo-600">
                        <label for="sameAddress" class="font-semibold">Usar esta dirección también para
                            facturación</label>
                    </div> --}}

                </div>

                {{-- Botones --}}
                <div class="flex justify-end mt-4 space-x-2">
                    @if ($addresses->count() > 0)
                        <x-button wire:click="$set('newAddress', false)">Cancelar</x-button>
                    @endif
                    <x-button wire:click="storeAddress">Guardar</x-button>
                </div>
            </div>
        </section>
    @endif

    {{-- Direcciones de envío --}}
    <section class="bg-[#c2bf92] rounded-lg shadow overflow-hidden mb-3">
        {{-- Encabezado --}}
        <header class="bg-gray-600 px-4 py-2">
            <h2 class="text-white text-lg">
                Seleccione una dirección de envío
            </h2>
        </header>

        {{-- Direcciones de envío --}}
        <div class="p-4">
            {{-- Lista de direcciones --}}
            @if ($addresses->count() > 0)
                {{-- Lista de direcciones --}}
                <ul class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    @foreach ($addresses as $address)
                        {{-- Una direccion --}}
                        <li class="{{ $address->is_shipping ? 'bg-indigo-200' : 'bg-white' }} rounded-lg shadow"
                            wire:key="addresses-{{ $address->id }}">
                            <div class="p-4 flex items-center">
                                {{-- Icono de ubicación --}}
                                <div>
                                    <i class="fa-solid fa-location-dot text-xl"></i>
                                </div>

                                {{-- Informacion de la ubicación --}}
                                <div class="flex-1 mx-4 text-xs">
                                    <p class="font-bold"></p>
                                    {{ $address->type == 1 ? 'Domicilio' : 'Sucursal' }}
                                    </p>
                                    <p>
                                        {{ $address->calle . ' ' . $address->numero }}
                                    </p>
                                    <p>
                                        {{ $address->ciudad . ', ' . $address->provincia }}
                                    </p>
                                    <p>
                                        CP: {{ $address->codigo_postal }}
                                    </p>
                                </div>

                                {{-- Botones de cada dirección --}}
                                <div class="text-xs text-gray-800 flex flex-col space-y-2">
                                    <button wire:click="setDefaultShippingAddress({{ $address->id }})">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    {{-- <button wire:click="editAddress({{ $address->id }})">
                                                       <i class="fa-solid fa-pen"></i>
                                                   </button> --}}
                                    <button wire:click="deleteAddress({{ $address->id }})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center">
                    No hay direcciones guardadas
                </p>
            @endif
        </div>
    </section>

    {{-- Dirección de facturación --}}
    <section class="bg-[#c2bf92] rounded-lg shadow overflow-hidden mb-3">
        {{-- Encabezado --}}
        <header class="bg-gray-600 px-4 py-2">
            <h2 class="text-white text-lg">
                Seleccione una dirección para la facturación
            </h2>
        </header>

        {{-- Direcciones de envío --}}
        <div class="p-4">
            {{-- Lista de direcciones --}}
            @if ($addresses->count() > 0)
                {{-- Lista de direcciones --}}
                <ul class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    @foreach ($addresses as $address)
                        {{-- Una direccion --}}
                        <li class="{{ $address->is_billing ? 'bg-red-200' : 'bg-white' }} rounded-lg shadow"
                            wire:key="addresses-{{ $address->id }}">
                            <div class="p-4 flex items-center">
                                {{-- Icono de ubicación --}}
                                <div>
                                    <i class="fa-solid fa-location-dot text-xl"></i>
                                </div>

                                {{-- Informacion de la ubicación --}}
                                <div class="flex-1 mx-4 text-xs">
                                    <p class="font-bold"></p>
                                    {{ $address->type == 1 ? 'Domicilio' : 'Sucursal' }}
                                    </p>
                                    <p>
                                        {{ $address->calle . ' ' . $address->numero }}
                                    </p>
                                    <p>
                                        {{ $address->ciudad . ', ' . $address->provincia }}
                                    </p>
                                    <p>
                                        CP: {{ $address->codigo_postal }}
                                    </p>
                                </div>

                                {{-- Botones de cada dirección --}}
                                <div class="text-xs text-gray-800 flex flex-col space-y-2">
                                    <button wire:click="setDefaultBillingAddress({{ $address->id }})">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    {{-- <button wire:click="editAddress({{ $address->id }})">
                                                               <i class="fa-solid fa-pen"></i>
                                                           </button> --}}
                                    <button wire:click="deleteAddress({{ $address->id }})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center">
                    No hay direcciones guardadas
                </p>
            @endif
        </div>
    </section>

    {{-- Informacion del receptor --}}
    <section class="bg-[#c2bf92] rounded-lg shadow overflow-hidden mb-3">
        {{-- Encabezado --}}
        <header class="bg-gray-600 px-4 py-2">
            <h2 class="text-white text-lg">
                Información del destinatario
            </h2>
        </header>

        <div class="p-4">
            @if ($newReceiver)
                {{-- Errores de validacion --}}
                <x-validation-errors class="mb-4"></x-validation-errors>

                {{-- Formulario --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

                    {{-- Nombre --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Nombre
                        </label>
                        <x-input type="text" placeholder="Nombre" class="w-full" wire:model="createReceiver.name" />
                    </div>

                    {{-- Apellidos --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Apellidos
                        </label>
                        <x-input type="text" placeholder="Apellidos" class="w-full"
                            wire:model="createReceiver.last_name" />
                    </div>

                    {{-- Documento --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Documento
                        </label>
                        <x-input type="text" placeholder="Documento" class="w-full"
                            wire:model="createReceiver.document_number" />
                    </div>

                    {{-- Telefono --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Telefono
                        </label>
                        <x-input type="text" placeholder="Telefono" class="w-full"
                            wire:model="createReceiver.phone" />
                    </div>

                    {{-- Email --}}
                    <div class="lg:col-span-2 space-y-1">
                        <label class="font-semibold">
                            Email
                        </label>
                        <x-input type="text" placeholder="Email" class="w-full"
                            wire:model="createReceiver.email" />
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end mt-4 space-x-2">
                    @if ($receivers->count() > 0)
                        <x-button wire:click="$set('newReceiver', false)">Cancelar</x-button>
                    @endif
                    <x-button wire:click="storeReceiver">Guardar</x-button>
                </div>
            @else
                {{-- Lista de receptores --}}
                @if ($receivers->count() > 0)
                    {{-- Lista de direcciones --}}
                    <ul class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        @foreach ($receivers as $receiver)
                            {{-- Una direccion --}}
                            <li class="{{ $receiver->default ? 'bg-green-200' : 'bg-white' }} rounded-lg shadow"
                                wire:key="receivers-{{ $receiver->id }}">
                                <div class="p-4 flex items-center">
                                    {{-- Icono de destinatario --}}
                                    <div>
                                        <i class="fa-solid fa-user"></i>
                                    </div>

                                    {{-- Informacion del receptor --}}
                                    <div class="flex-1 mx-4 text-xs">
                                        <p class="font-semibold">
                                            {{ $receiver->name }} {{ $receiver->last_name }}
                                        </p>
                                        <p>
                                            Documento: {{ $receiver->document }}
                                        </p>
                                        <p>
                                            {{ $receiver->phone }}
                                        </p>
                                        <p>
                                            {{ $receiver->email }}
                                        </p>
                                    </div>

                                    {{-- Botones de cada destinatario --}}
                                    <div class="text-xs text-gray-800 flex flex-col space-y-2">
                                        <button wire:click="setDefaultReceiver({{ $receiver->id }})">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                        {{-- <button wire:click="editReceiver({{ $receiver->id }})">
                                                               <i class="fa-solid fa-pen"></i>
                                                           </button> --}}
                                        <button wire:click="deleteReceiver({{ $receiver->id }})">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center">
                        No hay destinatarios guardados
                    </p>
                @endif

                <button class="btn btn-outline-gray w-full flex items-center justify-center mt-4"
                    wire:click="$set('newReceiver', true)">
                    Agregar otro destinatario <i class="fa-solid fa-plus ml-2"></i>
                </button>
            @endif
        </div>

    </section>

    {{-- Boton de comprar --}}
    <div>
        <button wire:click="validateBeforeCheckout" class="w-full">
            <span class="block w-full text-center btn btn-indigo mt-4">Continuar compra</span>
        </button>
    </div>
</div>
