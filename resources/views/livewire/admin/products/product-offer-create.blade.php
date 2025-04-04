<div>
    <div class="bg-gray-700 rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-xl font-bold text-gray-100 mb-4 border-b pb-2">Ofertas del Producto</h1>

        {{-- Oferta activa --}}
        <div class="mb-6">
            @if ($product->activeOffer())
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium text-green-800">Oferta activa:</span>
                    </div>
                    <div class="mt-2 pl-7">
                        <span class="text-lg font-semibold text-gray-900">{{ $product->activeOffer()->name }}</span>
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            {{ $product->activeOffer()->discount_percentage }}% de descuento
                        </span>
                    </div>
                </div>
            @else
                <div class="bg-gray-500 border-l-4 border-gray-400 p-4 rounded-r">
                    <div class="flex items-center text-gray-100">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Este producto no tiene ofertas activas</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Lista de ofertas disponibles --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-100 mb-3">Ofertas asociadas a este producto</h2>
            <div class="space-y-3">
                @forelse ($offers as $offer)
                    <div
                        class="flex justify-between bg-gray-600 items-center p-3 hover:bg-gray-500 rounded-lg transition-colors duration-150">
                        <div>
                            <span class="font-medium text-gray-100">{{ $offer->name }}</span>
                            <span class="ml-2 text-sm text-gray-200">({{ $offer->discount_percentage }}% de
                                descuento)</span>
                        </div>
                        @if ($product->activeOffer() && $product->activeOffer()->id === $offer->id)
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Activa</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        No hay ofertas disponibles
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Botón de asociar oferta --}}
        <div class="flex justify-end">
            <button wire:click="$set('showOfferModal', true)"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Nueva Oferta
            </button>
        </div>
    </div>

    {{-- Modal de ofertas --}}
    <x-dialog-modal wire:model="showOfferModal">
        <x-slot name="title">
            Crear Oferta
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-1">Nombre de la oferta</label>
                    <input type="text" wire:model="name"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Ej. Descuento de Verano">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-1">Descuento (%)</label>
                    <input type="number" wire:model="discount_percentage" min="1" max="99"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Ej. 20">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-white font-semibold mb-1">Descripción</label>
                <input wire:model="description" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                    placeholder="Detalles de la oferta..."></input>
            </div>

            {{-- Fechas de la oferta --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-white font-semibold mb-1">Fecha de inicio</label>
                    <input type="datetime-local" wire:model="start_date"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-1">Fecha de fin</label>
                    <input type="datetime-local" wire:model="end_date"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
            </div>

            {{-- Validaciones --}}
            <x-validation-errors class="mb-4" />
        </x-slot>

        <x-slot name="footer">
            <div class="space-x-3">
                <x-button class="ms-3" wire:click="createOffer" wire:loading.attr="disabled">
                    Crear Oferta
                </x-button>

                <x-secondary-button wire:click="$set('showOfferModal', false)" wire:loading.attr="disabled">
                    {{ __('Cerrar') }}
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
