<div class="space-y-6">
    <!-- Header y Estadísticas -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-purple-300 flex items-center">
                    <i class="fas fa-tags mr-3"></i>
                    Gestión de Ofertas
                </h1>
                <p class="text-gray-400 mt-1">Administra todas las ofertas de tu ecommerce</p>
            </div>
            <a href="{{ route('admin.offers.create') }}">
                <x-button class="bg-purple-600 hover:bg-purple-500">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Oferta
                </x-button>
            </a>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-sm">Total Ofertas</p>
                <p class="text-2xl font-bold text-white">{{ $totalOffers }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-green-900">
                <p class="text-gray-400 text-sm">Ofertas Activas</p>
                <p class="text-2xl font-bold text-green-400">{{ $activeOffers }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-red-900">
                <p class="text-gray-400 text-sm">Ofertas Expiradas</p>
                <p class="text-2xl font-bold text-red-400">{{ $expiredOffers }}</p>
            </div>
        </div>
    </section>

    <!-- Filtros y Buscador -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Buscador -->
            <div class="md:col-span-2">
                <x-label class="text-gray-300 mb-1" value="Buscar Ofertas" />
                <div class="relative">
                    <x-input wire:model.live.debounce.500ms="search"
                        class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500 pl-10"
                        placeholder="Nombre o descripción..." />
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Filtro por Estado -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Estado" />
                <select wire:model.live="statusFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="all">Todos</option>
                    <option value="active">Activas</option>
                    <option value="inactive">Inactivas</option>
                </select>
            </div>

            <!-- Filtro por Fecha -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Filtrar por Fecha" />
                <select wire:model.live="dateFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas</option>
                    <option value="active">Activas hoy</option>
                    <option value="upcoming">Próximas</option>
                    <option value="expired">Expiradas</option>
                </select>
            </div>
        </div>

        <!-- Reset Filtros -->
        <div class="flex justify-end mt-4">
            @if ($search || $statusFilter !== 'all' || $dateFilter)
                <x-button wire:click="clearFilters" type="button" class="bg-gray-700 hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>
                    Limpiar Filtros
                </x-button>
            @endif
        </div>
    </section>

    <!-- Tabla de Ofertas -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            <div class="flex items-center">
                                Nombre
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
                            Descuento
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('start_date')">
                            <div class="flex items-center">
                                Fechas
                                @if ($sortField === 'start_date')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Productos
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('is_active')">
                            <div class="flex items-center">
                                Estado
                                @if ($sortField === 'is_active')
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
                    @forelse($offers as $offer)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-md bg-purple-900 flex items-center justify-center">
                                        <i class="fas fa-tag text-purple-400"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $offer->name }}</div>
                                        <div class="text-sm text-gray-400 line-clamp-1">{{ $offer->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-900 text-purple-300">
                                    {{ $offer->discount_percentage }}% OFF
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">
                                    {{ $offer->start_date }}
                                    <span class="text-gray-400 mx-1">-</span>
                                    {{ $offer->end_date }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    @php
                                        $today = now();
                                        $status = '';
                                        $color = '';

                                        if ($today->between($offer->start_date, $offer->end_date)) {
                                            $status = 'Activa';
                                            $color = 'text-green-400';
                                        } elseif ($today->lt($offer->start_date)) {
                                            $status = 'Próxima';
                                            $color = 'text-yellow-400';
                                        } else {
                                            $status = 'Expirada';
                                            $color = 'text-red-400';
                                        }
                                    @endphp
                                    <span class="{{ $color }}">{{ $status }}</span>
                                    ({{ $offer->start_date->diffInDays($offer->end_date) }} días)
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ $offer->products_count }} productos</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span wire:click="toggleActive({{ $offer->id }})"
                                    class="cursor-pointer px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $offer->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                    {{ $offer->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.offers.edit', $offer->id) }}">
                                        <x-button class="bg-blue-600 hover:bg-blue-500 px-3 py-1 text-xs">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                No se encontraron ofertas con los filtros seleccionados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="bg-gray-800 px-6 py-3 border-t border-gray-700">
            {{ $offers->links() }}
        </div>
    </section>
</div>
