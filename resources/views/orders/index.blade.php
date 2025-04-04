<x-app-layout>
    <x-container class="px-6 py-8">
        <h1 class="text-2xl font-semibold text-gray-300 mb-6">Mis Pedidos</h1>

        {{-- Si no hay pedidos --}}
        @if ($orders->isEmpty())
            <p class="text-center text-gray-600">No tienes pedidos aún.</p>
        @else
            {{-- Tabla de pedidos --}}
            <div class="overflow-x-auto bg-gray-100 p-4 shadow-md rounded-lg">
                <table class="min-w-full bg-white rounded-lg shadow-md">
                    <thead class="bg-gray-200 text-gray-700 uppercase text-sm">
                        <tr>
                            <th class="px-4 py-3 text-left">Número de Orden</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Total</th>
                            <th class="px-4 py-3 text-left">Método de Pago</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($orders as $order)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-800">${{ number_format($order->total, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if ($order->payment_method == 1)
                                        <span class="text-indigo-600 font-medium">Tarjeta</span>
                                    @else
                                        <span class="text-gray-600">Otro</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @switch($order->status)
                                        @case(\App\Enums\OrderStatus::Pending)
                                            <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">Pendiente</span>
                                            @break
                                        @case(\App\Enums\OrderStatus::Processing)
                                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Completado</span>
                                            @break
                                        @case(\App\Enums\OrderStatus::Cancelled)
                                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Cancelado</span>
                                            @break
                                        @default
                                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">Desconocido</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    <a href=" {{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline font-medium">Ver Detalles</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-container>
</x-app-layout>
