<div>
    <section class="rounded-lg bg-slate-700 shadow-lg">
        <header class="border-b px-6 py-2">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-300">Opciones</h1>

                <x-button button wire:click="$set('newOption.openModal', true)">
                    Nueva
                </x-button>
            </div>
        </header>

        <div class="p-6">

            <div class="space-y-6">
                @foreach ($options as $option)
                    <div class="p-6 rounded-lg border-gray-500 border relative pb-2"
                        wire:key="option-{{ $option->id }}">
                        {{-- OPCIONES --}}
                        <div class="absolute bg-slate-700 -top-3 text-gray-400 px-3">
                            <span>
                                {{ $option->name }}
                            </span>

                            {{-- Boton para eliminar una opcion ya existente --}}
                            @if ($option->type == 1)
                                <button class="ml-2" onclick="confirmDelete({{ $option->id }},'option')">
                                    <i class="fa-solid fa-trash-can text-gray-300 hover:text-red-600"></i>
                                </button>
                            @endif
                        </div>

                        {{-- VALORES --}}
                        <div class="flex flex-wrap mb-3">
                            @foreach ($option->features as $feature)
                                @switch($option->type)
                                    @case(1)
                                        {{-- TEXTO --}}
                                        <span
                                            class="bg-blue-100 text-slate-500 text-sm font-medium m-1 px-2.5 py-0.5 rounded dark:bg-slate-900 dark:text-blue-300">
                                            {{ $feature->description }}

                                            {{-- Agregamos una X a cada feature para eliminar --}}
                                            <button class="ml-1" wire:click="removeAddedFeature({{ $feature->id }})">
                                                <i class="fa-solid fa-xmark text-gray-500 hover:text-red-600"></i>
                                            </button>
                                        </span>
                                    @break

                                    @case(2)
                                        {{-- COLOR --}}
                                        <div class="relative">
                                            <span
                                                class="inline-block h-6 w-6 shadow-lg rounded-full border-2 border-slate-500 mr-4"
                                                style="background-color: {{ $feature->value }}">
                                            </span>

                                            {{-- Agregamos una X a cada feature para eliminar --}}
                                            {{-- <button
                                                class="absolute z-10 left-4 -top-2 rounded-full bg-gray-400 w-4 h-4 flex justify-center items-center"
                                                wire:click="removeAddedFeature({{ $feature->id }})">
                                                <i class="fa-solid fa-xmark text-gray-900 text-xs hover:text-red-600"></i>
                                            </button> --}}
                                        </div>
                                    @break

                                    @default
                                @endswitch
                            @endforeach
                        </div>

                        {{-- Llamamos al componente para añadir features a una opcion ya creada --}}
                        <div>
                            @livewire('admin.options.add-new-feature', ['option' => $option], key('add-new-feature-' . $option->id))
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </section>

    {{-- UTILIZO EL MODAL PARA LA CREACION DE UNA NUEVA OPCION --}}
    <x-dialog-modal wire:model="newOption.openModal">
        <x-slot name="title">
            Crear nueva opción
        </x-slot>

        <x-slot name="content">
            <div class="w-full">

                {{-- NOMBRE --}}
                <div>
                    <x-label class="mb-1">
                        Nombre
                    </x-label>

                    {{-- relaciono el input con el wire:model de la opcion nueva --}}
                    <x-input wire:model="newOption.name" class="w-full" placeholder="Por ejemplo: tamaño, tela"></x-input>
                </div>

                {{-- TIPO --}}
                {{-- <div>
                    <x-label class="mb-1">
                        Tipo
                    </x-label>

                    <x-select class="w-full" wire:model.live="newOption.type">
                        <option value="1">
                            Texto
                        </option>
                        <option value="2">
                            Color
                        </option>
                    </x-select>
                </div> --}}

            </div>

            {{-- LINEA DIVISORIA --}}
            <div class="flex items-center mb-4">
                <hr class="flex-1">

                <span class="mx-2">Valores</span>

                <hr class="flex-1">
            </div>

            <div class="mb-4">
                {{-- CICLO PARA CONTROLAR LA CANTIDAD DE VALORES NUEVOS DE LA OPCION --}}
                @foreach ($newOption->features as $index => $feauture)
                    {{--  --}}
                    <div class="p-6 m-3 rounded-lg border bordergray-500 relative"
                        wire:key="feature-{{ $index }}">
                        {{-- boton para eliminar un feature --}}
                        <div class="absolute -top-3 px-4 bg-slate-800">
                            <button wire:click="removeFeature({{ $index }})">
                                <i class="fa-solid fa-trash text-red-500 hover:text-red-600"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-4">
                            {{-- VALOR --}}
                            <div>
                                <x-label class="mb-1">
                                    Valor
                                </x-label>

                                {{-- Switch para mostrar un input dependiendo del tipo seleccionado --}}
                                @switch($newOption->type)
                                    @case(1)
                                        {{-- Si el campo type es igual a 1 mostramos un input de texto --}}
                                        <x-input class="w-full" placeholder="Valor de la opción"
                                            wire:model="newOption.features.{{ $index }}.value">
                                        </x-input>
                                    @break

                                    @case(2)
                                        {{-- Si el campo type es igual a 2 mostramos un input de color --}}
                                        <div
                                            class="border border-gray-700 rounded-md h-[42px] flex items-center px-2 justify-between">
                                            {{-- el color que seleccionamos. El operador ?: pregunta si existe y tiene un valor no nulo --}}
                                            {{ $newOption->features[$index]['value'] ?: 'Seleccionar color' }}

                                            <x-input type="color"
                                                wire:model.live="newOption.features.{{ $index }}.value">
                                            </x-input>
                                        </div>
                                    @break

                                    @default
                                @endswitch
                            </div>

                            {{-- DESCRIPCION --}}
                            <div>
                                <x-label class="mb-1">
                                    Descripción
                                </x-label>

                                <x-input class="w-full" placeholder="Descripción"
                                    wire:model="newOption.features.{{ $index }}.description">

                                </x-input>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Boton para agregar un feature --}}
            <div class="flex justify-end mb-4">
                <button class="btn" wire:click="addFeature">
                    Agregar valor
                </button>
            </div>

            <x-validation-errors class="mb-4"></x-validation-errors>

        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="addOption">
                Crear
            </x-button>
        </x-slot>

    </x-dialog-modal>

    {{-- ALERTA JS PARA ELIMINAR UNA CARACTERISTA O UNA OPCION --}}
    @push('js')
        <script>
            function confirmDelete(id, type) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "No podrás volver atrás",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar de todas formas",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        switch (type) {
                            case 'option':
                                @this.call('deleteOption', id);
                                break;
                            case 'feature':
                                @this.call('deleteFeature', id);
                                break;
                        }
                    }
                });
            }
        </script>
    @endpush
</div>
