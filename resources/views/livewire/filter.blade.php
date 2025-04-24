<div class="bg-gray-900 py-12">
    <x-container class="md:flex px-4 space-y-8 md:space-y-0 md:space-x-8">

        {{-- Filtros --}}
        @if (count($options) > 0)
            <aside class="md:w-72 flex-shrink-0">
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-sliders-h mr-2 text-indigo-400"></i> Filtros
                    </h2>

                    <ul class="space-y-4">
                        @foreach ($options as $option)
                            <li x-data="{ open: false }" class="border-b border-gray-700 pb-4 last:border-0 last:pb-0">
                                <button
                                    class="flex items-center justify-between w-full px-4 py-3 bg-gray-700 text-gray-200 font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200 group"
                                    x-on:click="open = !open">
                                    <span class="flex items-center">
                                        <i
                                            class="fas fa-{{ $option['icon'] ?? 'tag' }} mr-3 text-indigo-400 group-hover:text-indigo-300"></i>
                                        {{ $option['name'] }}
                                    </span>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"
                                        x-bind:class="{ 'rotate-180': open }"></i>
                                </button>

                                <ul class="mt-3 space-y-2 pl-2" x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-1">
                                    @foreach ($option['features'] as $feature)
                                        <li
                                            class="flex items-center gap-3 px-3 py-2 hover:bg-gray-700 rounded-lg transition-colors duration-150">
                                            <x-checkbox value="{{ $feature['id'] }}" wire:model="selected_features"
                                                class="h-5 w-5 text-indigo-600 border-gray-500 focus:ring-indigo-500 bg-gray-700 rounded" />
                                            <span class="text-gray-300 text-sm uppercase">
                                                {{ $feature['description'] }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6 flex space-x-3">
                        <x-button wire:click="applyFilters"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
                            <i class="fas fa-check mr-2"></i> Aplicar
                        </x-button>
                        <x-button wire:click="resetFilters"
                            class="w-full bg-gray-700 hover:bg-gray-600 focus:ring-gray-500 text-gray-300">
                            <i class="fas fa-times mr-2"></i> Limpiar
                        </x-button>
                    </div>
                </div>
            </aside>
        @endif

        {{-- Contenido principal --}}
        <div class="flex-1">
            @if (count($products) == 0)
                <div class="bg-gray-800 rounded-xl p-8 text-center border border-gray-700">
                    <i class="fas fa-search-minus text-5xl text-indigo-400 mb-4"></i>
                    <h1 class="text-2xl font-bold text-white mb-2">
                        No se encontraron productos
                    </h1>
                    <p class="text-gray-400 mb-6">Prueba ajustando los filtros de búsqueda</p>
                    <x-button wire:click="resetFilters" class="bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-redo mr-2"></i> Reiniciar filtros
                    </x-button>
                </div>
            @else
                {{-- Barra de ordenamiento --}}
                <div
                    class="bg-gray-800 rounded-xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-between border border-gray-700">
                    <div class="flex items-center mb-3 sm:mb-0 w-full sm:w-auto">
                        <span class="text-gray-400 mr-3 whitespace-nowrap">
                            <i class="fas fa-sort-amount-down mr-2"></i> Ordenar por:
                        </span>
                        <div class="relative w-full sm:w-64">
                            <x-select wire:model.live="orderBy"
                                class="block w-full pl-3 pr-10 py-2 text-base bg-gray-700 border-gray-600 text-white focus:ring-indigo-500 focus:border-indigo-500 rounded-lg">
                                <option value="1">Relevancia</option>
                                <option value="2">Precio: Mayor a menor</option>
                                <option value="3">Precio: Menor a mayor</option>
                                <option value="4">Más recientes</option>
                            </x-select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-400 text-sm">
                        Mostrando {{ $products->firstItem() }}-{{ $products->lastItem() }} de {{ $products->total() }}
                        resultados
                    </div>
                </div>

                {{-- Lista de productos --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <article
                            class="group relative bg-gray-900 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                            {{-- Badge --}}
                            @if ($product->activeOffer())
                                <div class="absolute top-4 left-4 z-10">
                                    <span
                                        class="px-3 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center">
                                        -{{ $product->activeOffer()->discount_percentage }}% OFF
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                            @endif

                            {{-- Product Image --}}
                            <figure class="relative aspect-square w-full h-80 overflow-hidden">
                                <img src="{{ $product->image }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    alt="{{ $product->name }}" loading="lazy">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                            </figure>

                            {{-- Product Info --}}
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-white mb-2 line-clamp-2">
                                    {{ $product->name }}
                                </h2>

                                <div class="flex flex-col items-start mt-4">
                                    @if ($product->activeOffer())
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-gray-400 text-sm line-through">
                                                ${{ number_format($product->sale_price, 2) }}
                                            </span>
                                            <span class="text-xl font-bold text-[#CBFF99]">
                                                ${{ number_format($product->sale_price * (1 - $product->activeOffer()->discount_percentage / 100), 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xl font-bold text-[#CBFF99]">
                                            ${{ number_format($product->sale_price, 2) }}
                                        </span>
                                    @endif

                                    {{-- Stock Status --}}
                                    @if ($product->totalStock() <= 0)
                                        <span
                                            class="mt-3 px-3 py-1 text-xs font-medium text-white bg-gray-700 rounded-full">
                                            Agotado
                                        </span>
                                    @elseif($product->totalStock() <= 5)
                                        <span
                                            class="mt-3 px-3 py-1 text-xs font-medium text-white bg-yellow-600 rounded-full animate-pulse">
                                            Últimas unidades
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Quick View Button --}}
                            <div
                                class="absolute bottom-20 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('products.show', $product) }}"
                                    class="px-6 py-2 bg-[#CBFF99] hover:bg-white text-gray-900 font-medium rounded-full shadow-lg transition-colors duration-300 flex items-center">
                                    Ver producto
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </x-container>
</div>
