<x-app-layout>
    <x-container class="py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Columna izquierda - Información de envío y pago --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 lg:p-8 space-y-6">
                    <h1 class="text-2xl font-bold text-gray-800 border-b pb-4">Resumen de compra</h1>

                    {{-- Información del destinatario --}}
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                                Destinatario
                            </h2>
                            <a href="{{ route('shipping.index') }}"
                                class="text-sm text-indigo-600 hover:underline">Editar</a>
                        </div>
                        @if ($receiver)
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Nombre</p>
                                    <p class="font-medium">{{ $receiver->name }} {{ $receiver->last_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Documento</p>
                                    <p class="font-medium">{{ $receiver->document_number }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Email</p>
                                    <p class="font-medium">{{ $receiver->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Teléfono</p>
                                    <p class="font-medium">{{ $receiver->phone }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-red-500">No hay destinatario seleccionado</p>
                        @endif
                    </div>

                    {{-- Dirección de envío --}}
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-truck mr-2 text-indigo-600"></i>
                                Dirección de envío
                            </h2>
                            <a href="{{ route('shipping.index') }}"
                                class="text-sm text-indigo-600 hover:underline">Editar</a>
                        </div>
                        @if ($shipping_address)
                            <div class="text-sm">
                                <p class="font-medium">{{ $shipping_address->calle }} {{ $shipping_address->numero }}
                                </p>
                                <p class="text-gray-600">{{ $shipping_address->ciudad }},
                                    {{ $shipping_address->provincia }}</p>
                                <p class="text-gray-600">CP: {{ $shipping_address->codigo_postal }}</p>
                            </div>
                        @else
                            <p class="text-red-500">No hay dirección de envío seleccionada</p>
                        @endif
                    </div>

                    {{-- Dirección de facturación --}}
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-file-invoice-dollar mr-2 text-indigo-600"></i>
                                Dirección de facturación
                            </h2>
                            <a href="{{ route('shipping.index') }}"
                                class="text-sm text-indigo-600 hover:underline">Editar</a>
                        </div>
                        @if ($billing_address)
                            <div class="text-sm">
                                <p class="font-medium">{{ $billing_address->calle }} {{ $billing_address->numero }}
                                </p>
                                <p class="text-gray-600">{{ $billing_address->ciudad }},
                                    {{ $billing_address->provincia }}</p>
                                <p class="text-gray-600">CP: {{ $billing_address->codigo_postal }}</p>
                            </div>
                        @else
                            <p class="text-red-500">No hay dirección de facturación seleccionada</p>
                        @endif
                    </div>

                    {{-- Métodos de pago --}}
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-indigo-50 p-4 border-b">
                            <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-indigo-600"></i>
                                Método de pago
                            </h2>
                        </div>
                        <div class="p-4">
                            <label
                                class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="mercadopago" checked
                                    class="h-5 w-5 text-indigo-600">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="block font-medium">Tarjeta de crédito/débito</span>
                                        <div class="flex space-x-2">
                                            <img src="https://http2.mlstatic.com/frontend-assets/vpp-frontend/webbeds-logo.svg"
                                                alt="Mercado Pago" class="h-6">
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Pago seguro con Mercado Pago. Se abrirá una nueva ventana para completar el
                                        pago.
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna derecha - Resumen de compra --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-4">
                <div class="p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-800 border-b pb-4">Tu pedido</h2>

                    {{-- Lista de productos --}}
                    <ul class="divide-y divide-gray-200 mb-6">
                        @foreach ($content as $item)
                            <li class="py-4">
                                <div class="flex items-start">
                                    <img class="w-16 h-16 object-cover rounded-lg border border-gray-200"
                                        src="{{ $item->options['image'] }}" alt="{{ $item->name }}">
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between">
                                            <h3 class="font-medium text-gray-900">{{ $item->name }}</h3>
                                            <div class="text-right">
                                                @if (isset($item->options['original_price']) && $item->options['original_price'] != $item->price)
                                                    <span class="text-sm text-gray-500 line-through mr-1">
                                                        ${{ number_format($item->options['original_price'] * $item->qty, 2) }}
                                                    </span>
                                                @endif
                                                <span class="font-medium">
                                                    ${{ number_format($item->price * $item->qty, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($item->options->features)
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @foreach ($item->options->features as $feature)
                                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                                        {{ $feature }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <p class="mt-2 text-sm text-gray-500">Cantidad: {{ $item->qty }}</p>

                                        @if (!empty($item->options['offer']))
                                            <div class="mt-1 text-xs text-green-600">
                                                <i class="fas fa-tag mr-1"></i>
                                                Descuento: {{ $item->options['offer']['discount_percent'] ?? 0 }}%
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Resumen de precios --}}
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if ($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Descuento</span>
                                <span class="font-medium">-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-600">Envío</span>
                            <span class="font-medium">${{ number_format($shipping, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold pt-2 mt-4 border-t border-gray-200">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Botón de pago --}}
                    <form action="{{ route('payment.create') }}" method="POST" class="mt-8">
                        @csrf
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all duration-300 transform hover:scale-[1.01] flex items-center justify-center">
                            <i class="fas fa-lock mr-2"></i>
                            Pagar ${{ number_format($total, 2) }} con Mercado Pago
                        </button>
                    </form>


                    {{-- Garantías y seguridad --}}
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <i class="fas fa-shield-alt text-green-500 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-600">Pago 100% seguro</p>
                            </div>
                            <div>
                                <i class="fas fa-undo text-blue-500 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-600">Devolución garantizada</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
