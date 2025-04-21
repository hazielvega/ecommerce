<div class="space-y-6">
    <!-- Formulario de Oferta -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <header class="border-b border-gray-700 pb-4 mb-6">
            <h1 class="text-xl font-bold text-purple-300 flex items-center">
                <i class="fas fa-tag mr-2"></i>
                Editar Oferta: {{ $offer->name }}
            </h1>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Nombre de la Oferta *" />
                <x-input wire:model="name" class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500"
                    placeholder="Ej. Oferta de Verano" />
            </div>

            <!-- Porcentaje de Descuento -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Descuento (%) *" />
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

        <!-- Fechas y Estado -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Fecha de Inicio -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Fecha de Inicio *" />
                <x-input wire:model="start_date" type="datetime-local"
                    class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500" />
            </div>

            <!-- Fecha de Fin -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Fecha de Finalización *" />
                <x-input wire:model="end_date" type="datetime-local"
                    class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500" />
            </div>

            <!-- Estado -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Estado" />
                <label class="inline-flex items-center mt-2">
                    <input wire:model="is_active" type="checkbox"
                        class="form-checkbox h-5 w-5 text-purple-600 rounded bg-gray-700 border-gray-600 focus:ring-purple-500">
                    <span class="ml-2 text-gray-300">Oferta activa</span>
                </label>
            </div>
        </div>
    </section>

    <!-- Selección de Productos -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <header class="border-b border-gray-700 pb-4 mb-6">
            <h2 class="text-lg font-bold text-purple-300 flex items-center">
                <i class="fas fa-boxes mr-2"></i>
                Selección de Productos *
            </h2>
        </header>

        <!-- Filtros y Buscador -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Buscador -->
            <div class="md:col-span-2">
                <x-label class="text-gray-300 mb-1" value="Buscar Productos" />
                <div class="relative">
                    <x-input wire:model.live.debounce.500ms="search"
                        class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500 pl-10"
                        placeholder="Nombre del producto..." />
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Filtro por Categoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Filtrar por Categoría" />
                <select wire:model.live="selected_category"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Subcategoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Filtrar por Subcategoría" />
                <select wire:model.live="selected_subcategory"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500"
                    {{ !$selected_category ? 'disabled' : '' }}>
                    <option value="">Todas las subcategorías</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Botón para agregar todos los productos filtrados -->
        <div class="flex justify-end mb-4">
            <x-button wire:click="addProductsFromCategory" wire:loading.attr="disabled" type="button"
                class="bg-indigo-600 hover:bg-indigo-500">
                <i class="fas fa-layer-group mr-2"></i>
                Agregar Todos los Productos Filtrados
            </x-button>
        </div>

        <!-- Lista de Productos Disponibles -->
        <div class="mb-8">
            <h3 class="text-md font-semibold text-gray-300 mb-3">Productos Disponibles</h3>

            @if ($products->isEmpty())
                <p class="text-gray-400 text-center py-4">No se encontraron productos con los filtros seleccionados.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <div
                            class="border border-gray-700 rounded-lg p-3 hover:bg-gray-800 transition-colors {{ array_key_exists($product->id, $selected_products) ? 'bg-gray-800 border-purple-500' : '' }}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                                    <p class="text-sm text-purple-400">${{ number_format($product->sale_price, 2) }}</p>
                                </div>
                                <div>
                                    @if (array_key_exists($product->id, $selected_products))
                                        <button type="button" class="text-green-500">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @else
                                        <button type="button"
                                            wire:click="$set('selected_products.{{ $product->id }}', { id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->sale_price }}})"
                                            class="text-gray-400 hover:text-purple-500">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Productos Seleccionados -->
        <div>
            <h3 class="text-md font-semibold text-gray-300 mb-3">Productos Seleccionados
                ({{ count($selected_products) }})</h3>

            @if (empty($selected_products))
                <p class="text-gray-400 text-center py-4">No hay productos seleccionados.</p>
            @else
                <div class="space-y-3">
                    @foreach ($selected_products as $product)
                        <div
                            class="flex items-center justify-between bg-gray-800 rounded-lg p-3 border border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $product['name'] }}</p>
                                    <p class="text-xs text-gray-400">Precio original:
                                        ${{ number_format($product['price'], 2) }}</p>
                                    <p class="text-xs text-purple-400">Precio con descuento:
                                        ${{ number_format($product['price'] * (1 - $discount_percentage / 100), 2) }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeSelectedProduct({{ $product['id'] }})"
                                class="text-red-500 hover:text-red-400">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Botones de Acción -->
    <div class="flex justify-between">
        <x-button wire:navigate href="{{ route('admin.offers.index') }}" class="bg-gray-600 hover:bg-gray-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al listado
        </x-button>

        <div class="flex space-x-3">
            <x-button wire:click="updateOffer" wire:loading.attr="disabled"
                class="bg-purple-600 hover:bg-purple-500">
                <i class="fas fa-save mr-2"></i>
                Guardar Cambios
            </x-button>
        </div>
    </div>

    <x-validation-errors />
</div>
