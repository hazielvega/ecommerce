<div class="card">
    <h1 class="text-lg font-semibold mb-4 text-white">Crear nueva oferta</h1>

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


    {{-- Productos --}}
    <div class="mb-4 mt-4">
        <h2 class="text-lg font-semibold mb-4 text-white">Seleccione los productos:</h2>

        <div class="p-2">
            {{-- Lista de categorías --}}
            <ul>
                @foreach ($categories as $category)
                    <li x-data="{ openCategory: false }" class="mb-2">
                        {{-- Botón por categoría --}}
                        <button @click="openCategory = !openCategory"
                            class="w-full text-left bg-gray-800 text-white px-4 py-2 rounded-md focus:outline-none flex justify-between items-center">
                            <span>{{ $category->name }}</span>
                            <i x-show="!openCategory" class="fa-solid fa-chevron-down transition"></i>
                            <i x-show="openCategory" class="fa-solid fa-chevron-up transition"></i>
                        </button>

                        {{-- Lista de subcategorias (colapsable) --}}
                        <ul x-show="openCategory" x-collapse class="mt-2 border-l border-gray-500 pl-4">
                            <li class="mb-1">
                                {{-- Boton para seleccionar toda la categoría --}}
                                <label
                                    class="w-full text-left bg-gray-200 text-black px-4 py-2 rounded-md focus:outline-none flex justify-between items-center">
                                    <span>Seleccionar todos los productos de {{ $category->name }}</span>
                                    <input type="checkbox" wire:click="toggleCategory({{ $category->id }})"
                                        @if (collect($category->subcategories)->flatMap->products->pluck('id')->diff(array_keys($selected_products))->isEmpty()) checked @endif>
                                </label>
                            </li>
                            @foreach ($category->subcategories as $subcategory)
                                <li x-data="{ openSubcategory: false }" class="mb-1">
                                    {{-- Botón por subcategoría --}}
                                    <button @click="openSubcategory = !openSubcategory"
                                        class="w-full text-left bg-gray-800 text-white px-4 py-2 rounded-md focus:outline-none flex justify-between items-center">
                                        <span>{{ $subcategory->name }}</span>
                                        <i x-show="!openSubcategory" class="fa-solid fa-chevron-down transition"></i>
                                        <i x-show="openSubcategory" class="fa-solid fa-chevron-up transition"></i>
                                    </button>

                                    {{-- Lista de productos --}}
                                    <ul x-show="openSubcategory" x-collapse
                                        class="mt-2 border-l border-gray-500 pl-4 text-white">
                                        @if ($subcategory->products->isEmpty())
                                            <li class="mb-2">No hay productos en esta subcategoría.</li>
                                        @else
                                            {{-- Boton para seleccionar toda la subcategoría --}}
                                            <li class="mb-1">
                                                <label>
                                                    <input type="checkbox"
                                                        wire:click="toggleSubcategory({{ $subcategory->id }})"
                                                        @if ($subcategory->products->pluck('id')->diff(array_keys($selected_products))->isEmpty()) checked @endif>
                                                    <span>Seleccionar todos los productos de {{ $subcategory->name }} /
                                                        {{ $category->name }}</span>
                                                </label>
                                            </li>
                                            @foreach ($subcategory->products as $product)
                                                <li class="mb-2">
                                                    <label class="flex items-center">
                                                        <input type="checkbox"
                                                            wire:click="toggleProduct({{ $product->id }})"
                                                            @if (isset($selected_products[$product->id])) checked @endif>

                                                        <span>{{ $product->name }}</span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Boton para crear la oferta --}}
        <div class="flex justify-end mt-4">
            <x-button wire:click="createOffer">Crear oferta</x-button>
        </div>
    </div>
</div>
