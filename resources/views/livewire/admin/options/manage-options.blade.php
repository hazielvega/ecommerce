<div>
    <section class="rounded-lg bg-gray-800 shadow-xl border border-gray-700">
        <header class="border-b border-gray-700 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-purple-300">Gestión de Opciones</h1>

                <x-button button wire:click="$set('newOption.openModal', true)" class="bg-purple-600 hover:bg-purple-500">
                    <i class="fas fa-plus mr-2"></i> Nueva Opción
                </x-button>
            </div>
        </header>

        <div class="p-6">
            @if ($options->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-box-open text-4xl mb-3"></i>
                    <p>No hay opciones creadas aún</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($options as $option)
                        <div class="p-6 rounded-lg border border-gray-700 bg-gray-750 relative group hover:border-purple-500 transition-colors duration-200"
                            wire:key="option-{{ $option->id }}">
                            {{-- Encabezado de opción --}}
                            <div class="absolute bg-gray-800 -top-3 text-purple-300 px-3 font-medium flex items-center">
                                <span class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-sm"></i>
                                    {{ $option->name }}
                                </span>

                                {{-- Botón para eliminar --}}
                                @if ($option->type == 1)
                                    <button class="ml-2 text-gray-400 hover:text-red-400 transition-colors"
                                        onclick="confirmDelete({{ $option->id }},'option')">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                @endif
                            </div>

                            {{-- Valores/Features --}}
                            <div class="flex flex-wrap gap-2 mb-4 mt-2">
                                @foreach ($option->features as $feature)
                                    @switch($option->type)
                                        @case(1)
                                            {{-- TEXTO --}}
                                            <span class="relative group/feature">
                                                <span
                                                    class="bg-gray-900 text-purple-200 text-sm font-medium px-3 py-1 rounded-full flex items-center border border-gray-700">
                                                    {{ $feature->description }}
                                                    <button class="ml-1.5 text-gray-500 hover:text-red-400 transition-colors"
                                                        wire:click="removeAddedFeature({{ $feature->id }})">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </span>
                                                <span
                                                    class="absolute -bottom-5 left-0 bg-gray-900 text-xs text-gray-400 px-2 py-0.5 rounded opacity-0 group-hover/feature:opacity-100 transition-opacity">
                                                    {{ $feature->value }}
                                                </span>
                                            </span>
                                        @break

                                        @case(2)
                                            {{-- COLOR --}}
                                            <div class="relative group/color">
                                                <span
                                                    class="inline-block h-8 w-8 shadow-lg rounded-full border-2 border-gray-600 hover:border-purple-400 transition-colors cursor-pointer"
                                                    style="background-color: {{ $feature->value }}"
                                                    title="{{ $feature->description }} ({{ $feature->value }})">
                                                </span>
                                                <button
                                                    class="absolute -top-2 -right-2 z-10 bg-gray-700 rounded-full w-5 h-5 flex justify-center items-center border border-gray-600 opacity-0 group-hover/color:opacity-100 transition-opacity hover:bg-red-500 hover:border-red-400"
                                                    wire:click="removeAddedFeature({{ $feature->id }})">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                @endforeach
                            </div>

                            {{-- Componente para añadir nuevas features --}}
                            <div class="pt-2 border-t border-gray-700">
                                @livewire('admin.options.add-new-feature', ['option' => $option], key('add-new-feature-' . $option->id))
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Modal para nueva opción --}}
    <x-dialog-modal wire:model="newOption.openModal" maxWidth="2xl">
        <x-slot name="title">
            <div class="flex items-center text-purple-300">
                <i class="fas fa-plus-circle mr-2"></i>
                <span>Crear nueva opción</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                {{-- Nombre de la opción --}}
                <div>
                    <x-label class="mb-2 text-gray-300">
                        <i class="fas fa-tag mr-2 text-purple-300"></i>
                        Nombre de la opción
                    </x-label>
                    <x-input wire:model="newOption.name"
                        class="w-full bg-gray-700 border-gray-600 text-gray-200 focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Ejemplo: Tamaño, Color, Material"></x-input>
                </div>

                {{-- Tipo de opción --}}
                <div>
                    <x-label class="mb-2 text-gray-300">
                        <i class="fas fa-list-alt mr-2 text-purple-300"></i>
                        Tipo de valores
                    </x-label>
                    <select wire:model="newOption.type"
                        class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        <option value="1">Texto</option>
                        <option value="2">Color</option>
                    </select>
                </div>

                {{-- Divisor --}}
                <div class="flex items-center my-4">
                    <hr class="flex-1 border-gray-700">
                    <span class="mx-3 text-gray-400 font-medium">Valores</span>
                    <hr class="flex-1 border-gray-700">
                </div>

                {{-- Lista de features --}}
                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach ($newOption->features as $index => $feature)
                        <div class="p-4 rounded-lg border border-gray-700 bg-gray-750 relative group"
                            wire:key="feature-{{ $index }}">
                            {{-- Botón para eliminar --}}
                            <button wire:click="removeFeature({{ $index }})"
                                class="absolute -top-2 -right-2 bg-red-500 rounded-full w-5 h-5 flex justify-center items-center hover:bg-red-600 transition-colors z-10">
                                <i class="fas fa-times text-xs"></i>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Valor --}}
                                <div>
                                    <x-label class="mb-1 text-gray-300">
                                        Valor
                                    </x-label>
                                    @switch($newOption->type)
                                        @case(1)
                                            <x-input
                                                class="w-full bg-gray-700 border-gray-600 text-gray-200 focus:ring-purple-500"
                                                placeholder="Valor de la opción"
                                                wire:model="newOption.features.{{ $index }}.value">
                                            </x-input>
                                        @break

                                        @case(2)
                                            <div class="flex items-center gap-3">
                                                <div class="border border-gray-700 rounded-md h-10 w-10 flex-shrink-0"
                                                    style="background-color: {{ $feature['value'] ?? '#6b7280' }}"></div>
                                                <x-input type="color"
                                                    wire:model.live="newOption.features.{{ $index }}.value"
                                                    class="h-10 w-full bg-gray-700 border-gray-600">
                                                </x-input>
                                            </div>
                                        @break
                                    @endswitch
                                </div>

                                {{-- Descripción --}}
                                <div>
                                    <x-label class="mb-1 text-gray-300">
                                        Descripción (opcional)
                                    </x-label>
                                    <x-input
                                        class="w-full bg-gray-700 border-gray-600 text-gray-200 focus:ring-purple-500"
                                        placeholder="Descripción visible"
                                        wire:model="newOption.features.{{ $index }}.description">
                                    </x-input>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Botón para agregar más valores --}}
                <div class="flex justify-end pt-2">
                    <button
                        class="flex items-center text-sm bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-md transition-colors"
                        wire:click="addFeature">
                        <i class="fas fa-plus mr-2"></i> Agregar valor
                    </button>
                </div>

                <x-validation-errors class="mt-4"></x-validation-errors>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between w-full">
                <x-secondary-button wire:click="$set('newOption.openModal', false)">
                    Cancelar
                </x-secondary-button>
                <x-button wire:click="addOption" class="bg-purple-600 hover:bg-purple-500">
                    <i class="fas fa-save mr-2"></i> Crear opción
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Script para confirmación de eliminación --}}
    @push('js')
        <script>
            function confirmDelete(id, type) {
                Swal.fire({
                    title: "¿Confirmar eliminación?",
                    text: "Esta acción no se puede deshacer",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#7e22ce",
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    background: '#1f2937',
                    color: '#f3f4f6'
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

                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "El elemento ha sido eliminado.",
                            icon: "success",
                            confirmButtonColor: "#7e22ce",
                            background: '#1f2937',
                            color: '#f3f4f6'
                        });
                    }
                });
            }
        </script>
    @endpush
</div>
