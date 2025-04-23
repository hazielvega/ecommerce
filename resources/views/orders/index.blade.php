<x-app-layout>
    <div class="bg-gray-900 min-h-screen py-8">
        <x-container class="px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col">
                {{-- Encabezado --}}
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3 text-indigo-400"></i>
                        Mis Pedidos
                    </h1>

                    <a href="{{ route('welcome.index') }}"
                        class="text-indigo-400 hover:text-indigo-300 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a la tienda
                    </a>
                </div>

                {{-- Si no hay pedidos --}}
                @if ($orders->isEmpty())
                    <div class="bg-gray-800 rounded-xl p-8 text-center border border-gray-700 max-w-2xl mx-auto">
                        <i class="fas fa-shopping-bag text-4xl text-gray-500 mb-4"></i>
                        <h2 class="text-xl font-medium text-gray-300 mb-2">No tienes pedidos aún</h2>
                        <p class="text-gray-500 mb-6">Cuando realices un pedido, aparecerá aquí</p>
                        <a href="{{ route('welcome.index') }}"
                            class="inline-flex items-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-store mr-2"></i>
                            Ir a la tienda
                        </a>
                    </div>
                @else
                    {{-- Tabla de pedidos --}}
                    <div class="overflow-x-auto bg-gray-800 rounded-xl border border-gray-700 shadow-lg">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Número
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Pago
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-750 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">#{{ $order->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-white">
                                                ${{ number_format($order->total, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($order->payment_method == 1)
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-indigo-900/30 text-indigo-300 rounded-full inline-flex items-center">
                                                    <i class="fas fa-credit-card mr-1"></i>
                                                    Tarjeta
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-gray-700 text-gray-300 rounded-full">
                                                    Otro
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($order->status)
                                                @case(\App\Enums\OrderStatus::Pendiente)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-yellow-900/30 text-yellow-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Pendiente
                                                    </span>
                                                @break

                                                @case(\App\Enums\OrderStatus::Procesando)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-blue-900/30 text-blue-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-cog mr-1 animate-spin"></i>
                                                        Procesando
                                                    </span>
                                                @break

                                                @case(\App\Enums\OrderStatus::Enviado)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-purple-900/30 text-purple-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-truck mr-1"></i>
                                                        Enviado
                                                    </span>
                                                @break

                                                @case(\App\Enums\OrderStatus::Completado)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-green-900/30 text-green-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Completado
                                                    </span>
                                                @break

                                                @case(\App\Enums\OrderStatus::Fallido)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-red-900/30 text-red-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Fallido
                                                    </span>
                                                @break

                                                @case(\App\Enums\OrderStatus::Reembolsado)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-green-900/30 text-green-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-exchange-alt mr-1"></i>
                                                        Reembolsado
                                                    </span>
                                                @break

                                                @case(\App\Enums\OrderStatus::Cancelado)
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-red-900/30 text-red-300 rounded-full inline-flex items-center">
                                                        <i class="fas fa-ban mr-1"></i>
                                                        Cancelado
                                                    </span>
                                                @break

                                                @default
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold bg-gray-700 text-gray-300 rounded-full">
                                                        Desconocido
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="text-indigo-400 hover:text-indigo-300 transition-colors inline-flex items-center">
                                                Ver detalles
                                                <i class="fas fa-chevron-right ml-1 text-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </x-container>
    </div>
</x-app-layout>
