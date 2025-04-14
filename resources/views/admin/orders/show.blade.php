<x-admin-layout>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Detalle de Orden #{{ $order->id }}</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
                @if ($order->status->value < 4)
                    <button wire:click="markAsCompleted({{ $order->id }})" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i> Marcar como Completada
                    </button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Encabezado de la orden -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-xl font-semibold text-gray-800">Información de la Orden</h2>
                        <p class="text-sm text-gray-600">Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span
                            class="px-4 py-2 rounded-full text-sm font-medium 
                        @switch($order->status->value)
                            @case(1) bg-blue-100 text-blue-800 @break
                            @case(2) bg-yellow-100 text-yellow-800 @break
                            @case(3) bg-purple-100 text-purple-800 @break
                            @case(4) bg-green-100 text-green-800 @break
                            @case(5) bg-red-100 text-red-800 @break
                        @endswitch">
                            {{ $order->status->name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Cuerpo de la orden -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Detalles de los productos -->
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Productos</h3>
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Producto</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cantidad</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img src="{{ $item->variant->product->image ?? asset('img/placeholder.png') }}"
                                                            class="w-16 h-16 object-cover rounded-md mr-4"
                                                            alt="Producto">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->variant->product->name }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $item->variant->features->implode('description', ', ') ?? 'Caracteristicas desconocidas' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($item->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                ${{ number_format($item->price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Resumen de la orden -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Resumen</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if ($order->discount > 0)
                                    <div class="flex justify-between text-green-600">
                                        <span>Descuento:</span>
                                        <span class="font-medium">-${{ number_format($order->discount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Envío:</span>
                                    <span class="font-medium">${{ number_format($order->shipping, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>Total:</span>
                                        <span>${{ number_format($order->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del cliente -->
                            <div class="mt-8">
                                <h4 class="text-md font-semibold mb-2 text-gray-800">Cliente</h4>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <p class="text-gray-800 font-medium">
                                        {{ $order->user->name ?? $order->receiver->name }}
                                    </p>
                                    <p class="text-gray-600">{{ $order->user->email ?? $order->receiver->email }}</p>
                                    <p class="text-gray-600 mt-2">{{ $order->receiver->phone }}</p>
                                </div>
                            </div>

                            <!-- Dirección de envío -->
                            <div class="mt-6">
                                <h4 class="text-md font-semibold mb-2 text-gray-800">Dirección de Envío</h4>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <p> {{ $order->shippingAddress->provincia }} -
                                        {{ $order->shippingAddress->ciudad }}</p>
                                    <p> {{ $order->shippingAddress->calle }} - {{ $order->shippingAddress->numero }}
                                    </p>
                                    <p> Código Postal: {{ $order->shippingAddress->codigo_postal }}</p>
                                </div>
                            </div>

                            <!-- Dirección de facturación -->
                            <div class="mt-6">
                                <h4 class="text-md font-semibold mb-2 text-gray-800">Dirección de Facturación</h4>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <p> {{ $order->billingAddress->provincia }} - {{ $order->billingAddress->ciudad }}
                                    </p>
                                    <p> {{ $order->billingAddress->calle }} - {{ $order->billingAddress->numero }}
                                    </p>
                                    <p> Código Postal: {{ $order->billingAddress->codigo_postal }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie de página con acciones -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex justify-end space-x-4">
                    {{-- Botón para descargar el comprobante en PDF --}}
                    @if ($order->pdf_path)
                        <a href="{{ route('orders.download', $order) }}" target="_blank"
                            class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                            Descargar Comprobante
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
