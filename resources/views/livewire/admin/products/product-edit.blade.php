<div class="bg-gray-900 rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-edit mr-3 text-purple-500"></i> Editar Producto
        </h2>
    </div>

    <div class="p-6">
        <div class="flex items-center gap-4 mb-6">
            <!-- Estado del producto -->
            <div>
                @if (!$product['is_enabled'])
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-red-900/30 text-red-300 border border-red-700/50">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        DESHABILITADO
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-green-900/30 text-green-300 border border-green-700/50">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        HABILITADO
                    </span>
                @endif
            </div>

            <!-- Botón de acción -->
            <button wire:click="validateBeforeEnable" @class([
                'flex items-center px-4 py-2 rounded-md font-medium transition-colors',
                'bg-indigo-600 hover:bg-indigo-700 text-white' => $product['is_enabled'],
                'bg-gray-700 hover:bg-gray-600 text-gray-200' => !$product['is_enabled'],
            ])>
                @if ($product['is_enabled'])
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                    </svg>
                    Deshabilitar
                @else
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Habilitar
                @endif
            </button>
        </div>

        @if (!$product['is_enabled'] && !$product['has_enabled_variants'])
            <div class="mt-2 text-sm text-yellow-500">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Para habilitar este producto, primero debe tener al menos una variante habilitada.
            </div>
        @endif
    </div>

    <form wire:submit.prevent="update" class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Columna izquierda -->
            <div class="space-y-6">
                <!-- Subida de imágenes -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        Imágenes del producto (Máx. 5)
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="images"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-800 border-gray-700 hover:bg-gray-750 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-500 mb-2"></i>
                                <p class="text-sm text-gray-400">Haz clic o arrastra imágenes aquí</p>
                            </div>
                            <input id="images" type="file" multiple accept="image/*" wire:model="images"
                                class="hidden">
                        </label>
                    </div>
                    @error('images')
                        <span class="text-red-400 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Previsualización de imágenes existentes y nuevas -->
                    <div class="flex flex-wrap gap-3 mt-3">
                        <!-- Imágenes existentes -->
                        @foreach ($existingImages as $index => $image)
                            <div class="relative group">
                                <img src="{{ Storage::disk('public')->url($image) }}"
                                    class="w-20 h-20 object-cover rounded-md shadow-sm border border-gray-700">
                                <button type="button" wire:click="removeExistingImage({{ $index }})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    &times;
                                </button>
                            </div>
                        @endforeach

                        <!-- Previsualización de nuevas imágenes -->
                        @foreach ($previews as $index => $preview)
                            <div class="relative group">
                                <img src="{{ $preview }}"
                                    class="w-20 h-20 object-cover rounded-md shadow-sm border border-gray-700">
                                <button type="button" wire:click="removeImage({{ $index }})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Información básica -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Nombre del producto</label>
                        <input type="text" wire:model="product.name"
                            class="w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        @error('product.name')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                        <textarea wire:model="product.description" rows="3"
                            class="w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="space-y-6">
                <!-- Categoría y subcategoría -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Categoría</label>
                        <select wire:model.live="category_id"
                            class="w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Subcategoría</label>
                        <select wire:model="product.subcategory_id"
                            class="w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            @if (!$category_id) disabled @endif>
                            <option value="">Seleccione una subcategoría</option>
                            @foreach ($this->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('product.subcategory_id')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Código SKU</label>
                    <div class="flex">
                        <input type="text" wire:model="product.sku"
                            class="w-full rounded-l-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    @error('product.sku')
                        <span class="text-red-400 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precios -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Precio de compra</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">$</span>
                            <input type="number" step="0.01" min="0" wire:model="product.purchase_price"
                                class="pl-8 w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        @error('product.purchase_price')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Precio de venta</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">$</span>
                            <input type="number" step="0.01" min="{{ $product['purchase_price'] }}"
                                wire:model="product.sale_price"
                                class="pl-8 w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        @error('product.sale_price')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Stock Mínimo</label>
                    <input type="number" min="0" wire:model="product.min_stock"
                        class="w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('product.min_stock')
                        <span class="text-red-400 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Productos Relacionados -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-300">
                        Productos Relacionados (Máx. 8) - Seleccionados: {{ count($related_products) }}
                    </label>

                    <!-- Buscador de productos -->
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="searchTerm"
                            placeholder="Buscar productos..."
                            class="w-full rounded-md bg-gray-800 border-gray-700 text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                    </div>

                    <!-- Lista de productos disponibles -->
                    <div class="max-h-60 overflow-y-auto border border-gray-700 rounded-md mt-2 bg-gray-800">
                        <ul class="divide-y divide-gray-700">
                            @foreach ($availableProducts as $product)
                                <li class="px-4 py-2 hover:bg-gray-750 cursor-pointer flex justify-between items-center"
                                    wire:click="addRelatedProduct({{ $product->id }})">
                                    <div>
                                        <p class="font-medium text-white">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $product->sku }}</p>
                                    </div>
                                    <span class="text-xs bg-gray-700 px-2 py-1 rounded text-gray-300">
                                        ${{ number_format($product->sale_price, 2) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Productos seleccionados -->
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-300 mb-2">Productos seleccionados:</p>
                        <ul class="space-y-2">
                            @forelse($related_products as $index => $productId)
                                @php
                                    $relatedProduct =
                                        collect($availableProducts)->firstWhere('id', $productId) ??
                                        Product::find($productId);
                                @endphp
                                @if ($relatedProduct)
                                    <li class="flex items-center justify-between bg-gray-800 p-2 rounded-md">
                                        <span class="text-gray-200">{{ $relatedProduct->name }}</span>
                                        <button type="button" wire:click="removeRelatedProduct({{ $index }})"
                                            class="text-red-400 hover:text-red-300">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </li>
                                @endif
                            @empty
                                <p class="text-sm text-gray-500">No hay productos seleccionados</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="mt-8 flex justify-end space-x-4">
            {{-- <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition-colors">
                Cancelar
            </a> --}}
            <button type="submit"
                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i> Actualizar Producto
            </button>
        </div>
    </form>
</div>
