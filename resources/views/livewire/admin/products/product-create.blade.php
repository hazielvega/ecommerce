<div class="bg-gray-900 rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-plus-circle mr-3 text-purple-500"></i> Crear Nuevo Producto
        </h2>
    </div>

    <form wire:submit.prevent="store" class="p-6">
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

                    <!-- Previsualización de imágenes -->
                    <div class="flex flex-wrap gap-3 mt-3">
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
                        Productos Relacionados (Máx. 8) - Seleccionados:
                        {{ count($product['related_products'] ?? []) }}
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
                        {{-- @dump($related_products) --}}
                        <p class="text-sm font-medium text-gray-300 mb-2">Productos seleccionados:</p>
                        <ul class="space-y-2">
                            @forelse($related_products as $index => $productId)
                                @php
                                    // Buscar el producto en availableProducts o cargarlo de la base de datos si no está
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
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i> Guardar Producto
            </button>
        </div>
    </form>
</div>
