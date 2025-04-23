<div class="space-y-6">
    <!-- Header y Estadísticas -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-purple-300 flex items-center">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    Gestión de Órdenes
                </h1>
                <p class="text-gray-400 mt-1">Administra todas las órdenes de tu ecommerce</p>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-sm">Total Órdenes</p>
                <p class="text-2xl font-bold text-white">{{ $totalOrders }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-yellow-900">
                <p class="text-gray-400 text-sm">Órdenes Pendientes</p>
                <p class="text-2xl font-bold text-yellow-400">{{ $pendingOrders }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-green-900">
                <p class="text-gray-400 text-sm">Órdenes Completadas</p>
                <p class="text-2xl font-bold text-green-400">{{ $completedOrders }}</p>
            </div>
        </div>
    </section>

    <!-- Filtros y Buscador -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Buscador -->
            <div class="md:col-span-2">
                <x-label class="text-gray-300 mb-1" value="Buscar Ordenes" />
                <div class="relative">
                    <x-input wire:model.live.debounce.500ms="search"
                        class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500 pl-10"
                        placeholder="Nombre cliente o email..." />
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Filtro por Estado -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Estado" />
                <select wire:model.live="statusFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    @foreach ($statuses as $key => $status)
                        <option value="{{ $key }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Método de Pago -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Método de Pago" />
                <select wire:model.live="paymentMethodFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    @foreach ($paymentMethods as $key => $method)
                        <option value="{{ $key }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Fecha -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Filtrar por Fecha" />
                <select wire:model.live="dateFilter"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas</option>
                    <option value="today">Hoy</option>
                    <option value="week">Esta semana</option>
                    <option value="month">Este mes</option>
                </select>
            </div>
        </div>

        <!-- Reset Filtros -->
        <div class="flex justify-end mt-4">
            @if ($search || $statusFilter !== 'all' || $dateFilter || $paymentMethodFilter !== 'all')
                <x-button wire:click="clearFilters" type="button" class="bg-gray-700 hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>
                    Limpiar Filtros
                </x-button>
            @endif
        </div>
    </section>

    <!-- Tabla de Órdenes -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('total')">
                            <div class="flex items-center">
                                Total
                                @if ($sortField === 'total')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Método Pago
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('status')">
                            <div class="flex items-center">
                                Estado
                                @if ($sortField === 'status')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center">
                                Fecha
                                @if ($sortField === 'created_at')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-purple-400"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Detalles
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">
                                    {{ $order->user->name ?? 'Cliente sin registro' }}</div>
                                <div class="text-sm text-gray-400">{{ $order->user->email ?? $order->receiver->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                ${{ number_format($order->total + $order->shipping_cost, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $paymentMethods[$order->payment_method] ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-select wire:change="updateStatus({{ $order->id }}, $event.target.value)">
                                    <option value="" disabled selected>Cambiar estado</option>
                                    {{-- Pendiente --}}
                                    <option value="{{ \App\Enums\OrderStatus::Pendiente->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Pendiente)>
                                        Pendiente
                                    </option>
                                    {{-- Procesando --}}
                                    <option value="{{ \App\Enums\OrderStatus::Procesando->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Procesando)>
                                        Procesando
                                    </option>
                                    {{-- Shipped --}}
                                    <option value="{{ \App\Enums\OrderStatus::Enviado->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Enviado)>
                                        Enviado
                                    </option>
                                    {{-- Completed --}}
                                    <option value="{{ \App\Enums\OrderStatus::Completado->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Completado)>
                                        Completado
                                    </option>
                                    {{-- Failed --}}
                                    <option value="{{ \App\Enums\OrderStatus::Fallido->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Fallido)>
                                        Fallido
                                    </option>
                                    {{-- Refunded --}}
                                    <option value="{{ \App\Enums\OrderStatus::Reembolsado->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Reembolsado)>
                                        Reembolsado
                                    </option>
                                    {{-- Cancelled --}}
                                    <option value="{{ \App\Enums\OrderStatus::Cancelado->value }}"
                                        @selected($order->status === \App\Enums\OrderStatus::Cancelado)>
                                        Cancelado
                                    </option>
                                </x-select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    <div>
                                        <x-button wire:click="downloadTicket({{ $order->id }})">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </x-button>
                                    </div>

                                    <a href="{{ route('admin.orders.show', $order->id) }}">
                                        <x-button class="bg-blue-600 hover:bg-blue-500 px-3 py-1 text-xs">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                No se encontraron órdenes con los filtros seleccionados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="bg-gray-800 px-6 py-3 border-t border-gray-700">
            {{ $orders->links() }}
        </div>
    </section>
</div>
