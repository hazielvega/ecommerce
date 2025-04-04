<x-app-layout>
    <x-container class="px-6 py-8">
        <h1 class="text-2xl font-semibold text-gray-300 mb-6">Detalles del Pedido #{{ $order->id }}</h1>

        {{-- Información del Pedido --}}
        <div class="bg-white p-6 shadow-md rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Información General --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Información del Pedido</h2>
                    <p><span class="font-medium text-gray-700">Fecha:
                        </span>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</p>
                    <p><span class="font-medium text-gray-700">Total: </span>${{ number_format($order->total, 2) }}</p>

                    <p class="mt-2">
                        <span class="font-medium text-gray-700">Estado: </span>
                        @switch($order->status)
                            @case(\App\Enums\OrderStatus::Pending)
                                <span
                                    class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">Pendiente</span>
                            @break

                            @case(\App\Enums\OrderStatus::Processing)
                                <span
                                    class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Completado</span>
                            @break

                            @case(\App\Enums\OrderStatus::Cancelled)
                                <span
                                    class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Cancelado</span>
                            @break

                            @default
                                <span
                                    class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">Desconocido</span>
                        @endswitch
                    </p>
                </div>

                {{-- Método de Pago --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Método de Pago</h2>
                    @if ($order->payment_method == 1)
                        <p class="text-gray-700">Pago con tarjeta</p>
                        <p class="text-gray-600 text-sm">Número de tarjeta: <span
                                class="font-medium">{{ $order->card_number }}</span></p>
                        <p class="text-gray-600 text-sm">ID de pago: <span
                                class="font-medium">{{ $order->payment_id }}</span></p>
                    @else
                        <p class="text-gray-700">Otro método de pago</p>
                    @endif

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

        {{-- Información de Envío y Facturación --}}
        {{-- @dump($order->shippingAddress) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white p-6 shadow-md rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Dirección de Envío</h2>
                <p> {{ $order->shippingAddress->provincia }} - {{ $order->shippingAddress->ciudad }}</p>
                <p> {{ $order->shippingAddress->calle }} - {{ $order->shippingAddress->numero }} </p>
                <p> Código Postal: {{ $order->shippingAddress->codigo_postal }}</p>
            </div>

            <div class="bg-white p-6 shadow-md rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Dirección de Facturación</h2>
                <p> {{ $order->billingAddress->provincia }} - {{ $order->billingAddress->ciudad }}</p>
                <p> {{ $order->billingAddress->calle }} - {{ $order->billingAddress->numero }} </p>
                <p> Código Postal: {{ $order->billingAddress->codigo_postal }}</p>
            </div>
        </div>

        {{-- Productos Comprados --}}
        <div class="mt-6 bg-white p-6 shadow-md rounded-lg border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Productos Comprados</h2>

            <div class="divide-y divide-gray-200">
                @foreach ($order->items as $item)
                    <div class="flex items-center py-4">
                        <img src="{{ $item->variant->product->image ?? asset('img/placeholder.png') }}"
                            class="w-16 h-16 object-cover rounded-md mr-4" alt="Producto">
                        <div class="flex-1">
                            <p class="text-gray-700 font-medium uppercase">
                                {{ $item->variant->product->name ?? 'Producto desconocido' }}</p>
                            <p class="text-gray-600 font-medium">
                                {{ $item->variant->features->implode('description', ', ') ?? 'Caracteristicas desconocidas' }}
                            </p>
                            <p class="text-gray-600 text-sm">Cantidad: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-gray-800 font-semibold">${{ number_format($item->subtotal, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Botón para regresar --}}
        <div class="mt-6">
            <a href="{{ route('orders.index') }}"
                class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">
                Volver a Mis Pedidos
            </a>
        </div>

    </x-container>
</x-app-layout>
