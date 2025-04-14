<div class="bg-gray-900 min-h-screen p-6 rounded-lg">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-white">Reportes de Ventas</h1>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-300">Total:
                    ${{ number_format($sales->sum(fn($item) => $item->price * $item->quantity), 2) }}</span>
                <span class="text-sm text-gray-300">|</span>
                <span class="text-sm text-gray-300">{{ $sales->total() }} registros</span>
            </div>
        </div>

        <!-- Card principal -->
        <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Sección de filtros -->
            <div class="p-6 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-400"></i> Filtros de Búsqueda
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Filtro por categoría -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-300 mb-1">Categoría</label>
                        <div class="relative">
                            <x-select id="category" wire:model.live="selected_category"
                                class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="" class="bg-gray-700">Todas las categorías</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" class="bg-gray-700">{{ $category->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por subcategoría -->
                    <div>
                        <label for="subcategory"
                            class="block text-sm font-medium text-gray-300 mb-1">Subcategoría</label>
                        <div class="relative">
                            <x-select id="subcategory" wire:model.live="selected_subcategory"
                                class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="" class="bg-gray-700">Todas las subcategorías</option>
                                @foreach ($this->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" class="bg-gray-700">{{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-tags text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por fechas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Rango de Fechas</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="date" id="date_from" wire:model.live="date_from"
                                    class="bg-gray-700 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <label for="date_from"
                                    class="absolute -top-2 left-2 px-1 text-xs text-gray-300 bg-gray-800">Desde</label>
                            </div>
                            <div class="relative">
                                <input type="date" id="date_to" wire:model.live="date_to"
                                    class="bg-gray-700 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <label for="date_to"
                                    class="absolute -top-2 left-2 px-1 text-xs text-gray-300 bg-gray-800">Hasta</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de acción -->
                <div class="mt-6 flex justify-end">
                    <button wire:click="generateReport"
                        class="flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105">
                        <i class="fas fa-file-excel mr-2"></i> Generar Reporte
                    </button>
                </div>
            </div>

            <!-- Sección de resultados -->
            <div class="p-6">
                <div class="overflow-hidden rounded-lg shadow ring-1 ring-black ring-opacity-5">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors"
                                    wire:click="sortBy('created_at')">
                                    <div class="flex items-center">
                                        <i class="far fa-calendar-alt mr-2"></i> Fecha
                                        @if ($sortField === 'created_at')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fas fa-sort ml-1"></i>
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-box-open mr-2"></i> Producto
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors"
                                    wire:click="sortBy('quantity')">
                                    <div class="flex items-center">
                                        <i class="fas fa-layer-group mr-2"></i> Cantidad
                                        @if ($sortField === 'quantity')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fas fa-sort ml-1"></i>
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors"
                                    wire:click="sortBy('price')">
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2"></i> Precio Unit.
                                        @if ($sortField === 'price')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @else
                                            <i class="fas fa-sort ml-1"></i>
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-calculator mr-2"></i> Subtotal
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-receipt mr-2"></i> Orden #
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse ($sales as $item)
                                <tr class="hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $item->order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-white">{{ $item->variant->product->name }}
                                        </div>
                                        {{-- @if ($item->variant->features->isNotEmpty())
                                            <div class="text-xs text-gray-400 mt-1">
                                                <span class="inline-block bg-gray-700 rounded-full px-2 py-0.5 mr-1">
                                                    {{ $item->variant->features->pluck('name')->implode('</span><span class="inline-block bg-gray-700 rounded-full px-2 py-0.5 mr-1">') }}
                                                </span>
                                            </div>
                                        @endif --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-300">
                                        <span class="inline-block bg-blue-900 bg-opacity-30 rounded-full px-3 py-1">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        ${{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-400">
                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-400 hover:text-blue-300">
                                        <a href="{{ route('admin.orders.show', $item->order) }}"
                                            class="flex items-center">
                                            #{{ $item->order->id }}
                                            <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-inbox text-4xl mb-3"></i>
                                            <h3 class="text-lg font-medium">No se encontraron resultados</h3>
                                            <p class="text-sm">Prueba ajustando los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    @if ($sales->hasPages())
                        <div class="px-6 py-4 bg-gray-750 border-t border-gray-700">
                            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                <div class="text-sm text-gray-400">
                                    Mostrando {{ $sales->firstItem() }} a {{ $sales->lastItem() }} de
                                    {{ $sales->total() }} resultados
                                </div>

                                <div class="flex items-center space-x-1">
                                    <!-- Botón Anterior -->
                                    <button wire:click="previousPage" @if ($sales->onFirstPage()) disabled @endif
                                        class="px-3 py-1 rounded-md bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>

                                    <!-- Números de página -->
                                    @foreach ($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
                                        <button wire:click="gotoPage({{ $page }})"
                                            class="px-3 py-1 rounded-md {{ $sales->currentPage() == $page ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                                            {{ $page }}
                                        </button>
                                    @endforeach

                                    <!-- Botón Siguiente -->
                                    <button wire:click="nextPage" @if (!$sales->hasMorePages()) disabled @endif
                                        class="px-3 py-1 rounded-md bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <!-- Selector de items por página -->
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-400">Mostrar:</span>
                                    <select wire:model.live="perPage"
                                        class="bg-gray-700 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
