<div class="bg-gray-800 py-12">

    <x-container class="md:flex px-4">

        {{-- Listado de opciones para los filtros --}}
        @if (count($options) > 0)
            <aside class="md:w-60 md:flex-shrink-0 md:mr-8 mb-8 md:mb-0  p-4 shadow-md rounded-lg">
                {{-- Listado de opciones --}}
                <ul class="space-y-4">
                    @foreach ($options as $option)
                        <li x-data="{ open: false }">
                            {{-- Botón para abrir el listado de features --}}
                            <button
                                class="flex items-center uppercase justify-between w-full px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition duration-200"
                                x-on:click="open = !open">
                                {{ $option['name'] }}
                                <i class="fa-solid text-gray-900 transition-transform duration-200"
                                    x-bind:class="{ 'rotate-180': open }"></i>
                            </button>

                            {{-- Listado de features asociados a cada opción --}}
                            <ul class="mt-2 space-y-2 bg-gray-900 p-2 rounded-lg border border-gray-300" x-show="open"
                                x-transition.opacity.duration.300ms>
                                @foreach ($option['features'] as $feature)
                                    <li
                                        class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-200 transition duration-150 rounded-lg">
                                        {{-- Checkbox sincronizado --}}
                                        <x-checkbox value="{{ $feature['id'] }}" wire:model="selected_features"
                                            class="text-indigo-600 focus:ring-indigo-400" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $feature['description'] }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>

                {{-- Botón para aplicar los filtros --}}
                <div class="mt-6 text-center">
                    <x-button wire:click="applyFilters">
                        Aplicar filtros
                    </x-button>
                </div>
            </aside>
        @endif
        
        @if (count($products) == 0)
            <div class="md:flex-1">
                <h1 class="text-2xl font-bold mb-4 px-4 text-[#CBFF99]">
                    No se encontraron productos
                </h1>
            </div>
        @else
            {{-- Ordenamiento y lista de productos --}}
            <div class="md:flex-1">

                {{-- Select de ordenamiento --}}
                <div class="flex items-center rounded-lg bg-gray-200 p-2 shadow-sm">
                    <span class="mr-2 ml-3 text-gray-700 font-medium w-32">
                        Ordenar por:
                    </span>

                    {{-- Select de ordenamiento --}}
                    <div class="relative w-full">
                        <x-select wire:model.live="orderBy"
                            class="appearance-none w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            <option value="1">Relevancia</option>
                            <option value="2">Precio: Mayor a menor</option>
                            <option value="3">Precio: Menor a mayor</option>
                        </x-select>
                        <i
                            class="fa-solid fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    </div>
                </div>

                {{-- Linea divisoria --}}
                <hr class="my-4">

                {{-- @dump(array_shift($this->combinations)) --}}

                {{-- Lista de productos --}}
                {{-- @dump($products) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-0">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}"
                            class="group relative flex flex-col bg-gray-200 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
        
                            {{-- Badge de Oferta --}}
                            @if ($product->activeOffer())
                                <div class="absolute top-2 left-2 z-10">
                                    <span
                                        class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-md flex items-center">
                                        -{{ $product->activeOffer()->discount_percentage }}%
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                            @endif
        
                            {{-- Imagen del Producto --}}
                            <div class="relative h-80 w-full overflow-hidden">
                                <img src="{{ $product->image }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    alt="{{ $product->name }}" loading="lazy">
                            </div>
        
                            {{-- Contenido --}}
                            <div class="p-4 flex flex-col flex-grow items-center">
                                <h3 class="text-gray-800 font-medium text-sm mb-2 line-clamp-2">{{ $product->name }}</h3>
        
                                <div class="mt-auto">
                                    @if ($product->activeOffer())
                                        <div class="flex items-center justify-center space-x-2">
                                            <span class="text-gray-400 text-sm line-through">
                                                ${{ number_format($product->sale_price, 2) }}
                                            </span>
                                            <span class="text-lg font-bold text-red-600">
                                                ${{ number_format($product->sale_price * (1 - $product->activeOffer()->discount_percentage / 100), 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-lg font-semibold text-indigo-600">
                                            ${{ number_format($product->sale_price, 2) }}
                                        </span>
                                    @endif
                                </div>
        
                                {{-- Stock --}}
                                @if ($product->totalStock() <= 0)
                                    <span
                                        class="inline-block mt-2 px-2 py-1 text-xs text-white bg-gray-500 rounded-full">Agotado</span>
                                @elseif($product->totalStock() <= 5)
                                    <span
                                        class="inline-block mt-2 px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Últimas
                                        unidades</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        @endif

    </x-container>

</div>
