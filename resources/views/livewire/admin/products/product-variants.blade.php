<div class="space-y-6">
    <!-- Opciones y características -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700">
        <header class="border-b border-gray-700 px-6 py-4">
            <h1 class="text-lg font-semibold text-purple-300">
                Opciones del Producto
            </h1>
        </header>

        <div class="p-6">
            <ul class="space-y-3">
                @foreach ($options as $option)
                    <li x-data="{ open: false }" class="mb-2">
                        <button @click="open = !open"
                            class="w-full text-left bg-gray-700 hover:bg-gray-600 text-purple-200 px-4 py-3 rounded-md focus:outline-none flex justify-between items-center transition-colors">
                            <span class="font-medium">{{ $option->name }}</span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                class="fas transition-transform duration-200"></i>
                        </button>

                        <ul x-show="open" x-collapse class="mt-2 border-l-2 border-purple-500 pl-4 space-y-2">
                            @foreach ($option->features as $feature)
                                <li class="rounded-lg bg-gray-700 p-3 hover:bg-gray-600 transition-colors">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <x-checkbox value="{{ $feature->id }}" wire:model="selected_features"
                                            class="text-purple-500 border-gray-500" />
                                        <span class="text-gray-300">{{ $feature->description }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>

        <footer class="pb-6 pr-6 flex justify-end border-t border-gray-700 pt-4">
            <x-button wire:click="createVariants" class="bg-purple-600 hover:bg-purple-500 text-white">
                <i class="fas fa-save mr-2"></i> Generar Variantes
            </x-button>
        </footer>
    </section>

    <!-- Variantes -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700">
        <header class="border-b border-gray-700 px-6 py-4">
            <h1 class="text-lg font-semibold text-purple-300">
                Variantes Disponibles
            </h1>
        </header>

        <div class="p-6">
            @if ($enabledVariants->isEmpty())
                <div class="flex items-center p-4 rounded-lg bg-purple-900/30 text-purple-200 border border-purple-800">
                    <i class="fas fa-info-circle mr-3 text-purple-400"></i>
                    <div>
                        <p class="font-medium">No hay variantes disponibles</p>
                        <p class="text-sm text-purple-300">Selecciona opciones para generar variantes</p>
                    </div>
                </div>
            @else
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($enabledVariants as $variant)
                        <li
                            class="rounded-lg bg-gray-700 p-4 border border-gray-600 hover:border-purple-500 transition-colors">
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="col-span-2 space-y-2">
                                    @foreach ($variant->features as $feature)
                                        <p class="text-gray-300">
                                            <span
                                                class="font-medium text-purple-300">{{ $feature->option->name }}:</span>
                                            <span class="ml-2">{{ $feature->description }}</span>
                                        </p>
                                    @endforeach
                                </div>

                                <div class="flex flex-col space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Stock:</span>
                                        <span
                                            class="font-mono {{ $variant->stock <= $variant->min_stock ? 'text-red-400' : 'text-green-400' }}">
                                            {{ $variant->stock }} <span class="text-gray-500 text-xs">/
                                                {{ $variant->min_stock }}</span>
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Compra:</span>
                                        <span
                                            class="font-mono text-yellow-400">${{ number_format($variant->purchase_price, 2) }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Venta:</span>
                                        <span
                                            class="font-mono text-green-400">${{ number_format($variant->sale_price, 2) }}</span>
                                    </div>

                                    <button wire:click="editVariant({{ $variant }})"
                                        class="mt-2 text-xs text-purple-400 hover:text-purple-300 hover:underline flex items-center justify-end">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <!-- Modal de edición -->
    <x-dialog-modal wire:model="variantEdit.open" maxWidth="md">
        <x-slot name="title" class="text-purple-300">
            <i class="fas fa-edit mr-2"></i> Editar Variante
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label class="text-gray-300" value="Stock" />
                    <x-input type="number"
                        class="w-full bg-gray-700 text-gray-200 border-gray-600 focus:border-purple-500"
                        wire:model="variantEdit.stock" />
                </div>

                <div>
                    <x-label class="text-gray-300" value="Stock Mínimo" />
                    <x-input type="number"
                        class="w-full bg-gray-700 text-gray-200 border-gray-600 focus:border-purple-500"
                        wire:model="variantEdit.min_stock" />
                </div>

                <div>
                    <x-label class="text-gray-300" value="Precio de Compra" />
                    <x-input type="number" step="0.01"
                        class="w-full bg-gray-700 text-gray-200 border-gray-600 focus:border-purple-500"
                        wire:model="variantEdit.purchase_price" />
                </div>

                <div>
                    <x-label class="text-gray-300" value="Precio de Venta" />
                    <x-input type="number" step="0.01"
                        class="w-full bg-gray-700 text-gray-200 border-gray-600 focus:border-purple-500"
                        wire:model="variantEdit.sale_price" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('variantEdit.open', false)" class="bg-gray-700 hover:bg-gray-600">
                Cancelar
            </x-secondary-button>

            <x-button wire:click="updateVariant" class="ml-2 bg-purple-600 hover:bg-purple-500">
                Actualizar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
