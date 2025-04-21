<div class="space-y-6">
    <!-- Header -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-purple-300 flex items-center">
                    <i class="fas fa-receipt mr-3"></i>
                    Detalles de Orden #{{ $order->id }}
                </h1>
                <p class="text-gray-400 mt-1">
                    Creada el {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
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
            </div>
        </div>
    </section>

    <!-- Información General -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Cliente -->
        <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">
                <i class="fas fa-user mr-2"></i>
                Información del Cliente
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-400">Nombre</p>
                    <p class="text-white">{{ $order->user->name ?? 'Cliente sin registro' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Email</p>
                    <p class="text-white">{{ $order->user->email ?? $order->receiver->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Teléfono</p>
                    <p class="text-white">{{ $order->user->phone ?? $order->receiver->phone }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Documento</p>
                    <p class="text-white">{{ $order->user->document_number ?? $order->receiver->document_number }}</p>
                </div>
            </div>
        </section>

        <!-- Direcciones -->
        <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Direcciones
            </h2>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <h3 class="text-md font-medium text-purple-300 mb-2">Envío</h3>
                    @if ($order->shippingAddress)
                        <div class="text-sm text-gray-300 space-y-1">
                            <p>{{ $order->shippingAddress->calle }}, {{ $order->shippingAddress->numero }}</p>
                            <p>{{ $order->shippingAddress->ciudad }}, {{ $order->shippingAddress->provincia }}</p>
                            <p>Argentina - {{ $order->shippingAddress->codigo_postal }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No especificada</p>
                    @endif
                </div>

                <div>
                    <h3 class="text-md font-medium text-purple-300 mb-2">Facturación</h3>
                    @if ($order->billingAddress)
                        <div class="text-sm text-gray-300 space-y-1">
                            <p>{{ $order->billingAddress->calle }}, {{ $order->billingAddress->numero }}</p>
                            <p>{{ $order->billingAddress->ciudad }}, {{ $order->billingAddress->provincia }}</p>
                            <p>Argentina - {{ $order->billingAddress->codigo_postal }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No especificada</p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Resumen de Pago -->
        <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">
                <i class="fas fa-credit-card mr-2"></i>
                Resumen de Pago
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-400">Subtotal:</span>
                    <span class="text-white">${{ number_format($order->total, 2) }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Envío:</span>
                    <span class="text-white">${{ number_format($order->shipping_cost, 2) }}</span>
                </div>

                <div class="flex justify-between border-t border-gray-700 pt-2 mt-2">
                    <span class="text-gray-400 font-medium">Total:</span>
                    <span
                        class="text-white font-bold">${{ number_format($order->total + $order->shipping_cost, 2) }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-400">Método de pago</p>
                    <p class="text-white">
                        {{ match ($order->payment_method) {
                            1 => 'Tarjeta de Crédito',
                            2 => 'Transferencia Bancaria',
                            3 => 'Efectivo',
                            4 => 'PayPal',
                            default => 'Otro',
                        } }}
                    </p>
                </div>

                @if ($order->payment_id)
                    <div>
                        <p class="text-sm text-gray-400">ID de Pago</p>
                        <p class="text-white">{{ $order->payment_id }}</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Productos y Variantes -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">
            <i class="fas fa-boxes mr-2"></i>
            Productos
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Variante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Precio Unitario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Descuento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    @foreach ($order->orderItems as $item)
                        <tr class="hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gray-700 rounded-md flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">
                                            {{ $item->variant->product->name ?? 'Producto eliminado' }}</div>
                                        <div class="text-sm text-gray-400">SKU: {{ $item->variant->sku ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $item->variant->features->pluck('description')->implode(', ') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                @if ($item->discount_percentage > 0)
                                    {{ $item->discount_percentage }}%
                                    (${{ number_format($item->original_price - $item->price, 2) }})
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                ${{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>


    <!-- Historial de Estados -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white border-b border-gray-700 pb-3 mb-4">
            <i class="fas fa-history mr-2"></i>
            Historial de Estados
        </h2>

        <div class="space-y-4">
            @forelse($order->statusHistory()->latest()->get() as $history)
                <div class="border-l-4 border-purple-500 pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-white">
                                Cambiado de
                                <span
                                    class="{{ $statusColor($history->from_status) }} px-2 py-0.5 rounded-full text-xs">
                                    {{ $statusName($history->from_status) }}
                                    <!-- Cambiado de OrderHelper::statusName() -->
                                </span>
                                a
                                <span
                                    class="{{ $statusColor($history->to_status) }} px-2 py-0.5 rounded-full text-xs">
                                    {{ $statusName($history->to_status) }}
                                    <!-- Cambiado de OrderHelper::statusName() -->
                                </span>
                            </p>
                            @if ($history->notes)
                                <p class="text-sm text-gray-300 mt-1">
                                    <i class="fas fa-comment-alt mr-1"></i> {{ $history->notes }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">{{ $history->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500">por {{ $history->changedBy->name ?? 'Sistema' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-4">No hay historial de cambios para esta orden</p>
            @endforelse
        </div>
    </section>
</div>
