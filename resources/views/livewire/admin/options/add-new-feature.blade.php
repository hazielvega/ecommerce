<div>

    <form wire:submit="addFeature">
        <div class="lg:flex lg:space-x-4">

            {{-- Agregar caracteristicas a una opcion ya creada --}}
            <div class="lg:flex-1 text-gray-300">
                <x-label class="mb-1">
                    Agregar característica
                </x-label>

                {{-- Switch para mostrar un input dependiendo del tipo seleccionado --}}
                @switch($option->type)
                    @case(1)
                        {{-- Si el campo type es igual a 1 mostramos un input de texto --}}
                        <x-input class="w-full" placeholder="Valor de la opción" wire:model="newFeature.value">
                        </x-input>
                    @break

                    @case(2)
                        {{-- Si el campo type es igual a 2 mostramos un input de color --}}
                        <div class="border border-gray-700 rounded-md h-[42px] flex items-center px-2 justify-between">
                            {{-- el color que seleccionamos. El operador ?: pregunta si existe y tiene un valor no nulo --}}
                            {{ $newFeature['value'] ?: 'Seleccionar color' }}

                            <x-input type="color" wire:model.live="newFeature.value">
                            </x-input>
                        </div>
                    @break

                    @default
                @endswitch

            </div>

            {{-- Agregar una descripción a una opcion ya creada --}}
            <div class="lg:flex-1">
                <x-label class="mb-1">
                    Agregar descripción
                </x-label>

                <x-input class="w-full" placeholder="Descripción" wire:model="newFeature.description">
                </x-input>
            </div>

            {{-- Boton para agregar una nueva caracteristica --}}
            <div class="pt-6">
                <button class="btn btn-indigo w-full">
                    Agregar
                </button>
            </div>

        </div>
    </form>

</div>
