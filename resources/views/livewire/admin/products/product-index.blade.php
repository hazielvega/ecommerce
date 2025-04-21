<div class="space-y-6">
    <!-- Header y Estadísticas -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-purple-300 flex items-center">
                    <i class="fas fa-boxes mr-3"></i>
                    Gestión de Productos
                </h1>
                <p class="text-gray-400 mt-1">Administra todos los productos de tu ecommerce</p>
            </div>
            <a href="{{ route('admin.products.create') }}">
                <x-button class="bg-purple-600 hover:bg-purple-500">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Producto
                </x-button>
            </a>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-sm">Total Productos</p>
                <p class="text-2xl font-bold text-white">{{ $totalProducts }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-green-900">
                <p class="text-gray-400 text-sm">Productos Activos</p>
                <p class="text-2xl font-bold text-green-400">{{ $activeProducts }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-red-900">
                <p class="text-gray-400 text-sm">Sin Stock</p>
                <p class="text-2xl font-bold text-red-400">{{ $outOfStockProducts }}</p>
            </div>
        </div>
    </section>

    <!-- Filtros y Buscador -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Buscador -->
            <div class="md:col-span-2">
                <x-label class="text-gray-300 mb-1" value="Buscar Productos" />
                <div class="relative">
                    <x-input wire:model.live.debounce.500ms="search"
                        class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500 pl-10"
                        placeholder="Nombre, descripción o ID..." />
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Filtro por Estado -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Estado" />
                <select wire:model.live="statusFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="all">Todos</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
            </div>

            <!-- Filtro por Categoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Categoría" />
                <select wire:model.live="categoryFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Subcategoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Subcategoría" />
                <select wire:model.live="subcategoryFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500"
                    {{ !$categoryFilter ? 'disabled' : '' }}>
                    <option value="">Todas</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Filtros adicionales -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-4">
            <div class="md:col-span-2 flex items-center">
                <x-checkbox wire:model.live="stockAlertOnly" id="stockAlertOnly" />
                <x-label class="text-gray-300 ml-2" for="stockAlertOnly"
                    value="Mostrar solo productos con alerta de stock" />
            </div>
        </div>

        <!-- Reset Filtros -->
        <div class="flex justify-end mt-4">
            @if ($search || $statusFilter !== 'all' || $categoryFilter || $subcategoryFilter || $stockAlertOnly)
                <x-button wire:click="clearFilters" type="button" class="bg-gray-700 hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>
                    Limpiar Filtros
                </x-button>
            @endif
        </div>
    </section>

    <!-- Tabla de Productos -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('id')">
                            <div class="flex items-center">
                                ID
                                @if ($sortField === 'id')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            <div class="flex items-center">
                                Producto
                                @if ($sortField === 'name')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Variantes
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('sale_price')">
                            <div class="flex items-center">
                                Precio
                                @if ($sortField === 'sale_price')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('is_enabled')">
                            <div class="flex items-center">
                                Estado
                                @if ($sortField === 'is_enabled')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Editar
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                #{{ $product->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-md bg-gray-700 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $product->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $product->subcategory->category->name }} > {{ $product->subcategory->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- @livewire('admin.products.show-variants', ['product' => $product]) --}}
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-900 text-purple-300">
                                    {{ $product->variants_count }} variantes
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                ${{ number_format($product->sale_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_enabled ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                    {{ $product->is_enabled ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}">
                                        <x-button class="bg-blue-600 hover:bg-blue-500 px-3 py-1 text-xs">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                    </a>
                                    {{-- <a href="{{ route('admin.products.show', $product->id) }}">
                                        <x-button class="bg-gray-700 hover:bg-gray-600 px-3 py-1 text-xs">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                    </a> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                No se encontraron productos con los filtros seleccionados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="bg-gray-800 px-6 py-3 border-t border-gray-700">
            {{ $products->links() }}
        </div>
    </section>
</div>
