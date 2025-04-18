<x-app-layout>
    <x-container class="px-4 py-8">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">
                <i class="fas fa-receipt mr-2 text-indigo-600"></i> Detalles del Pedido #{{ $order->id }}
            </h1>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Mis Pedidos
            </a>
        </div>

        <!-- Panel principal -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <!-- Resumen del pedido -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <p class="text-lg font-semibold text-gray-800 mr-4">
                                Fecha: {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium 
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
                        <p class="text-sm text-gray-600 mt-1">ID de transacción: {{ $order->payment_id }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-indigo-600">${{ number_format($order->total, 2) }}</p>
                        <p class="text-sm text-gray-600">Total del pedido</p>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Productos -->
                    <div class="lg:col-span-2">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-boxes mr-2 text-gray-500"></i> Productos
                        </h2>

                        <div class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <div class="py-4">
                                    <div class="flex">
                                        <img src="{{ $item->variant->product->image_url ?? asset('img/placeholder.png') }}"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200 mr-4"
                                            alt="{{ $item->variant->product->name }}">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900">{{ $item->variant->product->name }}
                                            </h3>
                                            @if ($item->variant->features->count())
                                                <div class="mt-1 flex flex-wrap gap-1">
                                                    @foreach ($item->variant->features as $feature)
                                                        <span
                                                            class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                                            {{ $feature->description }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-gray-900 font-medium">${{ number_format($item->price, 2) }}
                                            </p>
                                            <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                                            @if ($item->discount_percentage > 0)
                                                <span
                                                    class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                                    -{{ $item->discount_percentage }}%
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-end">
                                        <p class="text-gray-900 font-semibold">
                                            Subtotal: ${{ number_format($item->subtotal, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Resumen y direcciones -->
                    <div>
                        <!-- Resumen de pago -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Resumen del Pago</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span>${{ number_format($order->items->sum('subtotal'), 2) }}</span>
                                </div>

                                @if ($order->items->sum('discount_percentage') > 0)
                                    <div class="flex justify-between text-green-600">
                                        <span>Descuentos:</span>
                                        <span>-${{ number_format(
                                            $order->items->sum('subtotal') -
                                                $order->items->sum(function ($item) {
                                                    return $item->original_price * $item->quantity;
                                                }),
                                            2,
                                        ) }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Envío:</span>
                                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                                </div>

                                <div class="border-t border-gray-200 pt-2 mt-2">
                                    <div class="flex justify-between font-bold">
                                        <span>Total:</span>
                                        <span>${{ number_format($order->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Método de pago -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Método de Pago</h3>
                            <div class="flex items-center">
                                @if ($order->payment_method == 1)
                                    <i class="fas fa-credit-card text-2xl text-indigo-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium">Tarjeta de crédito/débito</p>
                                        <p class="text-sm text-gray-600">Terminada en
                                            {{ substr($order->billing_document, -4) }}</p>
                                    </div>
                                @else
                                    <i class="fas fa-money-bill-wave text-2xl text-green-600 mr-3"></i>
                                    <p class="font-medium">Otro método</p>
                                @endif
                            </div>
                            @if ($order->pdf_path)
                                <a href="{{ route('orders.download', $order) }}"
                                    class="inline-flex items-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    <i class="fas fa-file-pdf mr-2"></i> Descargar comprobante
                                </a>
                            @endif
                        </div>

                        <!-- Dirección de envío -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-truck mr-2 text-gray-500"></i> Envío
                            </h3>
                            <div class="space-y-1">
                                <p class="font-medium">{{ $order->receiver->name }}</p>
                                <p class="text-gray-600">{{ $order->shippingAddress->calle }}
                                    {{ $order->shippingAddress->numero }}</p>
                                <p class="text-gray-600">{{ $order->shippingAddress->ciudad }},
                                    {{ $order->shippingAddress->provincia }}</p>
                                <p class="text-gray-600">CP: {{ $order->shippingAddress->codigo_postal }}</p>
                                <p class="text-gray-600">Tel: {{ $order->receiver->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
