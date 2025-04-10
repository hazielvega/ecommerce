<div class="space-y-6">
    {{-- Boton para agregar una nueva direccion --}}
    @if (!$newAddress)
        <button
            class="w-full py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:from-indigo-500 hover:to-indigo-600 transition-all flex items-center justify-center mb-6"
            wire:click="$set('newAddress', true)">
            <i class="fas fa-plus-circle mr-2"></i>
            Agregar nueva dirección
        </button>
    @endif

    {{-- Formulario para agregar una nueva direccion --}}
    @if ($newAddress)
        <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-700 border-b border-gray-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-amber-400"></i>
                    Nueva dirección
                </h3>
            </div>

            <div class="p-6">
                <x-validation-errors class="mb-6"></x-validation-errors>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Tipo de dirección --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Tipo de dirección</label>
                        <select wire:model="createAddress.type"
                            class="w-full bg-gray-700 text-gray-300 rounded-lg border border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seleccione un tipo</option>
                            <option value="1">Domicilio</option>
                            <option value="2">Sucursal</option>
                        </select>
                    </div>

                    {{-- Calle --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Calle</label>
                        <x-input type="text" placeholder="Nombre de la calle" class="w-full"
                            wire:model="createAddress.calle" />
                    </div>

                    {{-- Número --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Número</label>
                        <x-input type="text" placeholder="Número" class="w-full" wire:model="createAddress.numero" />
                    </div>

                    {{-- Ciudad --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Ciudad</label>
                        <x-input type="text" placeholder="Ciudad" class="w-full" wire:model="createAddress.ciudad" />
                    </div>

                    {{-- Provincia --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Provincia</label>
                        <x-input type="text" placeholder="Provincia" class="w-full"
                            wire:model="createAddress.provincia" />
                    </div>

                    {{-- Código Postal --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Código Postal</label>
                        <x-input type="text" placeholder="Código postal" class="w-full"
                            wire:model="createAddress.codigo_postal" />
                    </div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Referencia (opcional)</label>
                        <x-input type="text" placeholder="Ej: Portón blanco, casa con rejas negras" class="w-full"
                            wire:model="createAddress.description" />
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    @if ($addresses->count() > 0)
                        <button wire:click="$set('newAddress', false)"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-colors">
                            Cancelar
                        </button>
                    @endif
                    <button wire:click="storeAddress"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Guardar dirección
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Direcciones de envío --}}
    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-700 border-b border-gray-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-truck mr-2 text-amber-400"></i>
                Direcciones de envío disponibles
            </h3>
        </div>

        <div class="p-6">
            @if ($addresses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($addresses as $address)
                        <div
                            class="border rounded-lg overflow-hidden transition-all {{ $address->is_shipping ? 'border-amber-400 ring-2 ring-amber-400/30' : 'border-gray-600 hover:border-gray-500' }}">
                            <div class="p-4 bg-gray-700/50">
                                <div class="flex items-start">
                                    <div class="mr-4 text-amber-400">
                                        <i class="fas {{ $address->type == 1 ? 'fa-home' : 'fa-store' }} text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-white">
                                                {{ $address->type == 1 ? 'Domicilio' : 'Sucursal' }}
                                            </h4>
                                            <div class="flex space-x-2">
                                                <button wire:click="deleteAddress({{ $address->id }})"
                                                    class="text-gray-400 hover:text-red-400 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-300 mt-1">{{ $address->calle }}
                                            {{ $address->numero }}</p>
                                        <p class="text-sm text-gray-300">{{ $address->ciudad }},
                                            {{ $address->provincia }}</p>
                                        <p class="text-sm text-gray-300">CP: {{ $address->codigo_postal }}</p>
                                        @if ($address->description)
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-info-circle mr-1"></i> {{ $address->description }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-info-circle mr-1"></i> Sin descripción
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="setDefaultShippingAddress({{ $address->id }})"
                                    class="w-full mt-3 py-1.5 text-xs font-medium rounded-lg {{ $address->is_shipping ? 'bg-amber-500/20 text-amber-400' : 'bg-gray-600 hover:bg-gray-500 text-gray-300' }} transition-colors">
                                    {{ $address->is_shipping ? 'Seleccionada' : 'Seleccionar esta dirección' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-map-marked-alt text-4xl text-gray-500 mb-3"></i>
                    <p class="text-gray-400">No tienes direcciones guardadas</p>
                </div>
            @endif
        </div>
    </div>


    {{-- Dirección de facturación --}}
    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-700 border-b border-gray-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-truck mr-2 text-amber-400"></i>
                Direcciones de facturación disponibles
            </h3>
        </div>

        <div class="p-6">
            @if ($addresses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($addresses as $address)
                        <div
                            class="border rounded-lg overflow-hidden transition-all {{ $address->is_billing ? 'border-amber-400 ring-2 ring-amber-400/30' : 'border-gray-600 hover:border-gray-500' }}">
                            <div class="p-4 bg-gray-700/50">
                                <div class="flex items-start">
                                    <div class="mr-4 text-amber-400">
                                        <i class="fas {{ $address->type == 1 ? 'fa-home' : 'fa-store' }} text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-white">
                                                {{ $address->type == 1 ? 'Domicilio' : 'Sucursal' }}
                                            </h4>
                                            <div class="flex space-x-2">
                                                <button wire:click="deleteAddress({{ $address->id }})"
                                                    class="text-gray-400 hover:text-red-400 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-300 mt-1">{{ $address->calle }}
                                            {{ $address->numero }}</p>
                                        <p class="text-sm text-gray-300">{{ $address->ciudad }},
                                            {{ $address->provincia }}</p>
                                        <p class="text-sm text-gray-300">CP: {{ $address->codigo_postal }}</p>
                                        @if ($address->description)
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-info-circle mr-1"></i> {{ $address->description }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-info-circle mr-1"></i> Sin descripción
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="setDefaultBillingAddress({{ $address->id }})"
                                    class="w-full mt-3 py-1.5 text-xs font-medium rounded-lg {{ $address->is_billing ? 'bg-amber-500/20 text-amber-400' : 'bg-gray-600 hover:bg-gray-500 text-gray-300' }} transition-colors">
                                    {{ $address->is_billing ? 'Seleccionada' : 'Seleccionar esta dirección' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-map-marked-alt text-4xl text-gray-500 mb-3"></i>
                    <p class="text-gray-400">No tienes direcciones guardadas</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Información del destinatario --}}
    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden mt-6">
        <div class="px-6 py-4 bg-gray-700 border-b border-gray-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-user-tag mr-2 text-amber-400"></i>
                Información del destinatario
            </h3>
        </div>

        <div class="p-6">
            @if ($newReceiver)
                <x-validation-errors class="mb-6"></x-validation-errors>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Nombre</label>
                        <x-input type="text" placeholder="Nombre" class="w-full"
                            wire:model="createReceiver.name" />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Apellidos</label>
                        <x-input type="text" placeholder="Apellidos" class="w-full"
                            wire:model="createReceiver.last_name" />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Documento</label>
                        <x-input type="text" placeholder="Documento" class="w-full"
                            wire:model="createReceiver.document_number" />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Teléfono</label>
                        <x-input type="text" placeholder="Teléfono" class="w-full"
                            wire:model="createReceiver.phone" />
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Email</label>
                        <x-input type="email" placeholder="Email" class="w-full"
                            wire:model="createReceiver.email" />
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    @if ($receivers->count() > 0)
                        <button wire:click="$set('newReceiver', false)"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-colors">
                            Cancelar
                        </button>
                    @endif
                    <button wire:click="storeReceiver"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Guardar destinatario
                    </button>
                </div>
            @else
                @if ($receivers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($receivers as $receiver)
                            {{-- @dump($receiver) --}}
                            <div
                                class="border rounded-lg overflow-hidden transition-all {{ $receiver->default ? 'border-green-400 ring-2 ring-green-400/30' : 'border-gray-600 hover:border-gray-500' }}">
                                <div class="p-4 bg-gray-700/50">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-bold text-white flex items-center">
                                            <i class="fas fa-user mr-2"></i>
                                            {{ $receiver->name }} {{ $receiver->last_name }}
                                        </h4>
                                        <div class="flex space-x-2">
                                            <button wire:click="deleteReceiver({{ $receiver->id }})"
                                                class="text-gray-400 hover:text-red-400 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2 space-y-1 text-sm text-gray-300">
                                        <p><i class="fas fa-id-card mr-2"></i> {{ $receiver->document_number }}</p>
                                        <p><i class="fas fa-phone mr-2"></i> {{ $receiver->phone }}</p>
                                        <p><i class="fas fa-envelope mr-2"></i> {{ $receiver->email }}</p>
                                    </div>
                                    <button wire:click="setDefaultReceiver({{ $receiver->id }})"
                                        class="w-full mt-3 py-1.5 text-xs font-medium rounded-lg {{ $receiver->default ? 'bg-green-500/20 text-green-400' : 'bg-gray-600 hover:bg-gray-500 text-gray-300' }} transition-colors">
                                        {{ $receiver->default ? 'Seleccionado' : 'Seleccionar este destinatario' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="$set('newReceiver', true)"
                        class="w-full mt-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Agregar otro destinatario
                    </button>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-slash text-4xl text-gray-500 mb-3"></i>
                        <p class="text-gray-400 mb-4">No tienes destinatarios guardados</p>
                        <button wire:click="$set('newReceiver', true)"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors flex items-center mx-auto">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Agregar destinatario
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Botón de continuar --}}
    <div class="mt-8">
        <button wire:click="validateBeforeCheckout"
            class="w-full py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-lg shadow-lg hover:from-green-500 hover:to-green-600 transition-all flex items-center justify-center">
            <i class="fas fa-arrow-right mr-2"></i>
            Continuar con el pago
        </button>
    </div>
</div>
