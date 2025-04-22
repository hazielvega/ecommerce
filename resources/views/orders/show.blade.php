<x-app-layout>
    <div class="bg-gray-900 min-h-screen py-8">
        <x-container class="px-4 sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-4 md:mb-0">
                    <i class="fas fa-receipt mr-2 text-indigo-400"></i> Detalles del Pedido #{{ $order->id }}
                </h1>
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg border border-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Mis Pedidos
                </a>
            </div>

            <!-- Panel principal -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700">
                <!-- Resumen del pedido -->
                <div class="bg-gray-750 px-6 py-4 border-b border-gray-700">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div class="mb-4 md:mb-0">
                            <div class="flex flex-wrap items-center gap-4">
                                <p class="text-lg font-semibold text-gray-300">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium 
                                    @switch($order->status->value)
                                        @case(1) bg-blue-900/30 text-blue-300 @break
                                        @case(2) bg-yellow-900/30 text-yellow-300 @break
                                        @case(3) bg-purple-900/30 text-purple-300 @break
                                        @case(4) bg-green-900/30 text-green-300 @break
                                        @case(5) bg-red-900/30 text-red-300 @break
                                    @endswitch">
                                    <i
                                        class="fas 
                                        @switch($order->status->value)
                                            @case(1) fa-clock @break
                                            @case(2) fa-cog @break
                                            @case(3) fa-truck @break
                                            @case(4) fa-check-circle @break
                                            @case(5) fa-times-circle @break
                                        @endswitch
                                    mr-1"></i>
                                    {{ $order->status->name }}
                                </span>
                            </div>
                            @if ($order->payment_id)
                                <p class="text-sm text-gray-400 mt-2">
                                    <i class="fas fa-hashtag mr-1"></i>
                                    {{ $order->payment_id }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-indigo-400">${{ number_format($order->total, 2) }}</p>
                            <p class="text-sm text-gray-400">Total del pedido</p>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Productos -->
                        <div class="lg:col-span-2">
                            <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-box-open mr-2 text-gray-400"></i> Productos
                            </h2>

                            <div class="divide-y divide-gray-700">
                                @foreach ($order->items as $item)
                                    <div class="py-4">
                                        <div class="flex">
                                            <img src="{{ $item->variant->product->image ?? asset('img/placeholder.png') }}"
                                                class="w-16 h-16 object-cover rounded-lg border border-gray-700 mr-4"
                                                alt="{{ $item->variant->product->name }}">
                                            <div class="flex-1">
                                                <h3 class="font-medium text-white">{{ $item->variant->product->name }}
                                                </h3>
                                                @if ($item->variant->features->count())
                                                    <div class="mt-2 flex flex-wrap gap-2">
                                                        @foreach ($item->variant->features as $feature)
                                                            <span
                                                                class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded-full border border-gray-600">
                                                                {{ $feature->description }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-white font-medium">${{ number_format($item->price, 2) }}
                                                </p>
                                                <p class="text-sm text-gray-400">x{{ $item->quantity }}</p>
                                                @if ($item->discount_percentage > 0)
                                                    <span
                                                        class="text-xs bg-green-900/30 text-green-300 px-2 py-0.5 rounded-full mt-1 inline-block">
                                                        -{{ $item->discount_percentage }}%
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <p class="text-white font-semibold">
                                                Subtotal: ${{ number_format($item->subtotal, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Resumen y direcciones -->
                        <div class="space-y-6">
                            <!-- Resumen de pago -->
                            <div class="bg-gray-750 rounded-lg p-4 border border-gray-700">
                                <h3 class="text-lg font-semibold text-white mb-3 flex items-center">
                                    <i class="fas fa-file-invoice-dollar mr-2 text-gray-400"></i> Resumen del Pago
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Subtotal:</span>
                                        <span
                                            class="text-white">${{ number_format($order->items->sum('subtotal'), 2) }}</span>
                                    </div>

                                    @if ($order->items->sum('discount_percentage') > 0)
                                        <div class="flex justify-between text-green-400">
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
                                        <span class="text-gray-400">Envío:</span>
                                        <span class="text-white">${{ number_format($order->shipping_cost, 2) }}</span>
                                    </div>

                                    <div class="border-t border-gray-700 pt-3 mt-2">
                                        <div class="flex justify-between font-bold">
                                            <span class="text-white">Total:</span>
                                            <span class="text-indigo-400">${{ number_format($order->total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Método de pago -->
                            <div class="bg-gray-750 rounded-lg p-4 border border-gray-700">
                                <h3 class="text-lg font-semibold text-white mb-3 flex items-center">
                                    <i class="fas fa-credit-card mr-2 text-gray-400"></i> Método de Pago
                                </h3>
                                <div class="flex items-center">
                                    @if ($order->payment_method == 1)
                                        <div class="bg-indigo-900/30 p-2 rounded-lg mr-3 text-indigo-400">
                                            <i class="fas fa-credit-card text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Tarjeta de crédito/débito</p>
                                            @if ($order->billing_document)
                                                <p class="text-sm text-gray-400">
                                                    Terminada en {{ substr($order->billing_document, -4) }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-green-900/30 p-2 rounded-lg mr-3 text-green-400">
                                            <i class="fas fa-money-bill-wave text-xl"></i>
                                        </div>
                                        <p class="font-medium text-white">Otro método</p>
                                    @endif
                                </div>
                                @if ($order->pdf_path)
                                    <a href="{{ route('orders.download', $order) }}"
                                        class="inline-flex items-center mt-4 text-indigo-400 hover:text-indigo-300 text-sm font-medium transition-colors">
                                        <i class="fas fa-file-pdf mr-2"></i> Descargar comprobante
                                    </a>
                                @endif
                            </div>

                            <!-- Dirección de envío -->
                            <div class="bg-gray-750 rounded-lg p-4 border border-gray-700">
                                <h3 class="text-lg font-semibold text-white mb-3 flex items-center">
                                    <i class="fas fa-truck mr-2 text-gray-400"></i> Dirección de Envío
                                </h3>
                                <div class="space-y-2">
                                    <p class="font-medium text-white">{{ $order->receiver->name }}</p>
                                    <p class="text-gray-400">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                                        {{ $order->shippingAddress->calle }} {{ $order->shippingAddress->numero }}
                                    </p>
                                    <p class="text-gray-400">
                                        <i class="fas fa-city mr-2 text-gray-500"></i>
                                        {{ $order->shippingAddress->ciudad }},
                                        {{ $order->shippingAddress->provincia }}
                                    </p>
                                    <p class="text-gray-400">
                                        <i class="fas fa-mail-bulk mr-2 text-gray-500"></i>
                                        CP: {{ $order->shippingAddress->codigo_postal }}
                                    </p>
                                    <p class="text-gray-400">
                                        <i class="fas fa-phone mr-2 text-gray-500"></i>
                                        {{ $order->receiver->phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-container>
    </div>
</x-app-layout>
