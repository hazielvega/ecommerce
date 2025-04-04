<div>
    {{-- Opciones y caracteristicas --}}
    <section class="rounded-lg bg-slate-700 shadow-lg">
        {{-- Opciones y caracteristicas --}}
        <div>
            {{-- Encabezado --}}
            <header class="border-b px-6 py-2">
                <div>
                    <h1 class="text-lg font-semibold text-gray-300">
                        Opciones
                    </h1>
                </div>
            </header>

            {{-- Lista de opciones discponibles --}}
            <div class="p-6">
                <ul>
                    @foreach ($options as $option)
                        <li x-data="{ open: false }" class="mb-2">
                            {{-- Botón de opción --}}
                            <button @click="open = !open"
                                class="w-full text-left bg-gray-800 text-white px-4 py-2 rounded-md focus:outline-none flex justify-between items-center">
                                <span>{{ $option->name }}</span>
                                <i x-show="!open" class="fa-solid fa-chevron-down transition"></i>
                                <i x-show="open" class="fa-solid fa-chevron-up transition"></i>
                            </button>
            
                            {{-- Lista de características (colapsable) --}}
                            <ul x-show="open" x-collapse class="mt-2 border-l border-gray-500 pl-4">
                                @foreach ($option->features as $feature)
                                    <li class="rounded-lg mb-1 bg-gray-500 p-2">
                                        <label class="inline-flex items-center">
                                            {{-- Checkbox sincronizado --}}
                                            <x-checkbox value="{{ $feature->id }}" wire:model="selected_features"
                                                class="mr-2 ml-4" />
                                            {{ $feature->description }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Footer --}}
            <div class="pb-6 pr-6 flex justify-end">
                <x-button class="ml-2" wire:click="createVariants">
                    Guardar
                </x-button>
            </div>
        </div>
    </section>

    {{-- Variantes --}}
    <section class="rounded-lg bg-slate-700 shadow-lg mt-6">
        {{-- Encabezado --}}
        <header class="border-b px-6 py-2">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-300">
                    Variantes
                </h1>
            </div>
        </header>

        {{-- Listado de variantes asociadas al producto --}}
        <div class="grid p-6">
            @if ($product->variants->count() == 0)
                {{-- Alerta lista vacia --}}
                <div class="flex items-center p-4 text-sm text-gray-800 rounded-lg bg-gray-50 dark:bg-blue-900 dark:text-gray-300"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">
                            No hay variantes relacionadas a este producto. Debe agregar opciones para generar variantes.
                        </span>
                    </div>
                </div>
            @else
                <ul class="grid grid-cols-1 lg:grid-cols-2 gap-4 uppercase">
                    {{-- Iteracion sobre las variantes --}}
                    @foreach ($enabledVariants as $item)
                        <li class="rounded-lg bg-gray-900 p-4 text-white">
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="col-span-2">
                                    @foreach ($item->features as $feature)
                                        <p class="flex flex-col">
                                            <span class="px-3">
                                                {{ $feature['description'] }}
                                            </span>
                                        </p>
                                    @endforeach
                                </div>
                                <div class="flex flex-col space-y-2 items-center">
                                    {{-- Stock --}}
                                    <div class="space-x-2">
                                        <span class="text-sm text-yellow-400">
                                            Stock: {{ $item->stock }}    
                                        </span>
                                        {{-- Stock minimo --}}
                                        <span class="text-sm text-red-500">
                                            Min: {{ $item->min_stock }}
                                        </span>
                                    </div>


                                    {{-- Precio de compra --}}
                                    <span class="text-sm text-gray-400">
                                        Compra: ${{ $item->purchase_price }}
                                    </span>

                                    {{-- Precio de venta --}}
                                    <span class="text-sm text-gray-400">
                                        Venta: ${{ $item->sale_price }}
                                    </span>

                                    {{-- Boton para editar la variante --}}
                                    <button wire:click="editVariant({{ $item }})"
                                        class="text-sm text-blue-500 hover:underline">
                                        Editar
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- Editar una variante --}}
    <x-dialog-modal wire:model="variantEdit.open">
        {{-- Titulo del modal --}}
        <x-slot name="title">
            Editar variante
        </x-slot>

        {{-- Contenido del modal --}}
        <x-slot name="content">
            {{-- Stock --}}
            <div class="mb-4">
                <x-label>
                    Stock
                </x-label>

                <x-input class="w-full" wire:model="variantEdit.stock"></x-input>
            </div>

            {{-- Stock minimo --}}
            <div class="mb-4">
                <x-label>
                    Stock minimo
                </x-label>

                <x-input class="w-full" wire:model="variantEdit.min_stock"></x-input>
            </div>

            {{-- Precio de compra --}}
            <div class="mb-4">
                <x-label>
                    Precio de compra
                </x-label>

                <x-input class="w-full" wire:model="variantEdit.purchase_price"></x-input>
            </div>

            {{-- Precio de venta --}}
            <div class="mb-4">
                <x-label>
                    Precio de venta
                </x-label>

                <x-input class="w-full" wire:model="variantEdit.sale_price"></x-input>
            </div>

            {{-- ERRORES DE VALIDACION --}}
            <x-validation-errors class="mb-4"></x-validation-errors>
        </x-slot>

        {{-- Footer del modal --}}
        <x-slot name="footer">
            {{-- Boton para cancelar --}}
            <x-danger-button x-on:click="show = false">
                Cancelar
            </x-danger-button>

            {{-- Boton para confirmar --}}
            <x-button class="ml-2" wire:click="updateVariant">
                Actualizar
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>
