<div class="space-y-6">
    <!-- Ofertas del Producto -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <header class="border-b border-gray-700 pb-4 mb-6">
            <h1 class="text-xl font-bold text-purple-300 flex items-center">
                <i class="fas fa-tag mr-2"></i>
                Ofertas del Producto
            </h1>
        </header>

        <!-- Oferta Activa -->
        <div class="mb-8">
            @if($activeOffer)
                <div class="bg-green-900/20 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <div class="flex items-center text-green-300">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="font-semibold">OFERTA ACTIVA</span>
                    </div>
                    <div class="mt-3 pl-6">
                        <h3 class="text-lg font-bold text-white">{{ $activeOffer->name }}</h3>
                        <div class="flex flex-wrap items-center gap-4 mt-2">
                            <span class="px-3 py-1 bg-green-900/40 text-green-300 rounded-full text-sm font-medium">
                                {{ $activeOffer->discount_percentage }}% DESCUENTO
                            </span>
                            <span class="text-gray-400 text-sm">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $activeOffer->start_date->format('d M Y') }} - 
                                {{ $activeOffer->end_date->format('d M Y') }}
                            </span>
                        </div>
                        <p class="mt-2 text-gray-300">{{ $activeOffer->description }}</p>
                        
                        <button wire:click="deactivateOffer({{ $activeOffer->id }})" 
                                class="mt-3 text-sm text-red-400 hover:text-red-300 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i>
                            Desactivar oferta
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-gray-700 border-l-4 border-gray-500 p-4 rounded-r-lg">
                    <div class="flex items-center text-gray-400">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>Este producto no tiene ofertas activas</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Listado de Ofertas -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-purple-200 mb-4 flex items-center">
                <i class="fas fa-list-ul mr-2"></i>
                Ofertas Disponibles
            </h2>
            
            <div class="space-y-3">
                @forelse($offers as $offer)
                    <div class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 transition-colors duration-200 border border-gray-600">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-white">{{ $offer->name }}</h3>
                                <div class="flex items-center mt-1 space-x-4 text-sm">
                                    <span class="text-purple-300">
                                        {{ $offer->discount_percentage }}% descuento
                                    </span>
                                    <span class="text-gray-400">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $offer->start_date->format('d M') }} - {{ $offer->end_date->format('d M Y') }}
                                    </span>
                                    <span class="text-gray-400">
                                        {{ $offer->products_count }} producto(s)
                                    </span>
                                </div>
                            </div>
                            
                            @if($activeOffer && $activeOffer->id === $offer->id)
                                <span class="px-3 py-1 bg-green-900/30 text-green-300 text-xs font-bold rounded-full">
                                    ACTIVA
                                </span>
                            @else
                                <button wire:click="activateOffer({{ $offer->id }})"
                                        class="px-3 py-1 bg-purple-900/30 hover:bg-purple-800/50 text-purple-300 text-xs font-bold rounded-full transition-colors">
                                    ACTIVAR
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500 bg-gray-700 rounded-lg">
                        <i class="fas fa-box-open text-2xl mb-2"></i>
                        <p>No hay ofertas disponibles</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Botón para crear nueva oferta -->
        <div class="flex justify-end">
            <button wire:click="$set('showOfferModal', true)"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white font-medium rounded-lg shadow-md transition-colors flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Crear Nueva Oferta
            </button>
        </div>
    </section>

    <!-- Modal para crear oferta -->
    <x-dialog-modal wire:model="showOfferModal" maxWidth="2xl">
        <x-slot name="title">
            <div class="flex items-center text-purple-300">
                <i class="fas fa-tag mr-2"></i>
                Crear Nueva Oferta
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <x-label class="text-gray-300 mb-1" value="Nombre de la Oferta" />
                    <x-input wire:model="name" 
                             class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500" 
                             placeholder="Ej. Oferta de Verano" />
                </div>

                <!-- Porcentaje de Descuento -->
                <div>
                    <x-label class="text-gray-300 mb-1" value="Porcentaje de Descuento" />
                    <div class="relative">
                        <x-input wire:model="discount_percentage" type="number" min="1" max="99"
                                 class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500 pl-10" />
                        <span class="absolute left-3 top-2.5 text-gray-400">%</span>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="mt-6">
                <x-label class="text-gray-300 mb-1" value="Descripción (Opcional)" />
                <textarea wire:model="description" rows="3"
                          class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500"></textarea>
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Fecha de Inicio -->
                <div>
                    <x-label class="text-gray-300 mb-1" value="Fecha de Inicio" />
                    <x-input wire:model="start_date" type="datetime-local"
                             class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500" />
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <x-label class="text-gray-300 mb-1" value="Fecha de Finalización" />
                    <x-input wire:model="end_date" type="datetime-local"
                             class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500" />
                </div>
            </div>

            <x-validation-errors class="mt-4" />
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showOfferModal', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button wire:click="createOffer" wire:loading.attr="disabled" class="ml-2 bg-purple-600 hover:bg-purple-500">
                <i class="fas fa-save mr-2"></i>
                Guardar Oferta
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>