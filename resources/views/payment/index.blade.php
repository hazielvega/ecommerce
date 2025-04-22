<x-app-layout>
    <div class="bg-gray-900 min-h-screen">
        <x-container class="py-8 lg:py-12 w-[85%]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                {{-- Columna izquierda - Información de envío y pago --}}
                <div class="bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-700">
                    <div class="p-6 lg:p-8 space-y-8">
                        <h1
                            class="text-2xl lg:text-3xl font-bold text-white border-b border-gray-700 pb-6 flex items-center">
                            <i class="fas fa-shopping-bag mr-3 text-indigo-400"></i>
                            Resumen de compra
                        </h1>

                        {{-- Información del destinatario --}}
                        <div class="bg-gray-700/50 rounded-xl p-5 border border-gray-600">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-gray-200 flex items-center">
                                    <i class="fas fa-user-circle mr-3 text-indigo-400"></i>
                                    Información del destinatario
                                </h2>
                                <a href="{{ route('shipping.index') }}"
                                    class="text-sm text-indigo-400 hover:text-indigo-300 font-medium flex items-center transition-colors">
                                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                                </a>
                            </div>
                            @if ($receiver)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-700">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Nombre completo
                                        </p>
                                        <p class="font-medium text-white">{{ $receiver->name }}
                                            {{ $receiver->last_name }}</p>
                                    </div>
                                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-700">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Documento</p>
                                        <p class="font-medium text-white">{{ $receiver->document_number }}</p>
                                    </div>
                                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-700">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Email</p>
                                        <p class="font-medium text-white">{{ $receiver->email }}</p>
                                    </div>
                                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-700">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Teléfono</p>
                                        <p class="font-medium text-white">{{ $receiver->phone }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-900/30 border border-red-800 rounded-lg p-4 text-center">
                                    <i class="fas fa-exclamation-circle text-red-400 text-xl mb-2"></i>
                                    <p class="text-red-300 font-medium">No hay destinatario seleccionado</p>
                                    <a href="{{ route('shipping.index') }}"
                                        class="text-indigo-400 text-sm hover:underline mt-2 inline-block transition-colors">
                                        Seleccionar destinatario
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Dirección de envío --}}
                        <div class="bg-gray-700/50 rounded-xl p-5 border border-gray-600">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-gray-200 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-3 text-indigo-400"></i>
                                    Dirección de envío
                                </h2>
                                <a href="{{ route('shipping.index') }}"
                                    class="text-sm text-indigo-400 hover:text-indigo-300 font-medium flex items-center transition-colors">
                                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                                </a>
                            </div>
                            @if ($shipping_address)
                                <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                                    <div class="flex items-start">
                                        <div class="bg-indigo-900/30 p-2 rounded-lg text-indigo-400 mr-3">
                                            <i class="fas fa-home text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $shipping_address->calle }}
                                                {{ $shipping_address->numero }}</p>
                                            <p class="text-gray-300">{{ $shipping_address->ciudad }},
                                                {{ $shipping_address->provincia }}</p>
                                            <p class="text-gray-400 text-sm mt-1">
                                                <i class="fas fa-mail-bulk mr-1"></i> CP:
                                                {{ $shipping_address->codigo_postal }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-900/30 border border-red-800 rounded-lg p-4 text-center">
                                    <i class="fas fa-exclamation-circle text-red-400 text-xl mb-2"></i>
                                    <p class="text-red-300 font-medium">No hay dirección de envío seleccionada</p>
                                    <a href="{{ route('shipping.index') }}"
                                        class="text-indigo-400 text-sm hover:underline mt-2 inline-block transition-colors">
                                        Agregar dirección
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Dirección de facturación --}}
                        <div class="bg-gray-700/50 rounded-xl p-5 border border-gray-600">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-gray-200 flex items-center">
                                    <i class="fas fa-file-invoice-dollar mr-3 text-indigo-400"></i>
                                    Dirección de facturación
                                </h2>
                                <a href="{{ route('shipping.index') }}"
                                    class="text-sm text-indigo-400 hover:text-indigo-300 font-medium flex items-center transition-colors">
                                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                                </a>
                            </div>
                            @if ($billing_address)
                                <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                                    <div class="flex items-start">
                                        <div class="bg-indigo-900/30 p-2 rounded-lg text-indigo-400 mr-3">
                                            <i class="fas fa-building text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $billing_address->calle }}
                                                {{ $billing_address->numero }}</p>
                                            <p class="text-gray-300">{{ $billing_address->ciudad }},
                                                {{ $billing_address->provincia }}</p>
                                            <p class="text-gray-400 text-sm mt-1">
                                                <i class="fas fa-mail-bulk mr-1"></i> CP:
                                                {{ $billing_address->codigo_postal }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-amber-900/20 border border-amber-800 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle text-amber-400 mr-3 text-xl"></i>
                                        <p class="text-amber-300">Usaremos la dirección de envío para facturación</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Métodos de pago --}}
                        <div class="border border-gray-700 rounded-xl overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-800 to-indigo-900 p-4">
                                <h2 class="text-lg font-semibold text-white flex items-center">
                                    <i class="fas fa-credit-card mr-3"></i>
                                    Método de pago
                                </h2>
                            </div>
                            <div class="p-5 bg-gray-800">
                                <label
                                    class="flex items-start p-4 border border-gray-700 rounded-xl cursor-pointer hover:border-indigo-500 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-900/20">
                                    <input type="radio" name="payment_method" value="mercadopago" checked
                                        class="h-5 w-5 text-indigo-600 mt-1">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="block font-medium text-white">Tarjeta de crédito/débito</span>
                                            <div class="flex space-x-2">
                                                <img src="https://http2.mlstatic.com/frontend-assets/vpp-frontend/webbeds-logo.svg"
                                                    alt="Mercado Pago" class="h-6 invert opacity-90">
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-400">
                                            Pago seguro con Mercado Pago. Se abrirá una nueva ventana para completar el
                                            pago.
                                        </p>
                                        <div class="mt-3 flex items-center text-xs text-gray-500">
                                            <i class="fas fa-lock mr-2 text-indigo-400"></i>
                                            <span>Protegido con encriptación SSL de 256-bit</span>
                                        </div>
                                    </div>
                                </label>

                                <div class="mt-4 flex items-center text-sm text-gray-400">
                                    <i class="fas fa-shield-alt mr-2 text-green-400"></i>
                                    <span>No almacenamos los datos de tu tarjeta</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna derecha - Resumen de compra --}}
                <div class="bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-700 sticky top-8">
                    <div class="p-6 lg:p-8">
                        <h2 class="text-2xl font-bold text-white border-b border-gray-700 pb-6 flex items-center">
                            <i class="fas fa-receipt mr-3 text-indigo-400"></i>
                            Detalles del pedido
                        </h2>

                        {{-- Lista de productos --}}
                        <ul class="divide-y divide-gray-700 mb-6">
                            @foreach ($content as $item)
                                <li class="py-5">
                                    <div class="flex items-start">
                                        <img class="w-16 h-16 object-cover rounded-xl border border-gray-700 shadow-sm"
                                            src="{{ $item->options['image'] }}" alt="{{ $item->name }}">
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between">
                                                <h3 class="font-medium text-white">{{ $item->name }}</h3>
                                                <div class="text-right">
                                                    @if (isset($item->options['original_price']) && $item->options['original_price'] != $item->price)
                                                        <span class="text-sm text-gray-500 line-through mr-1">
                                                            ${{ number_format($item->options['original_price'] * $item->qty, 2) }}
                                                        </span>
                                                    @endif
                                                    <span class="font-medium text-white">
                                                        ${{ number_format($item->price * $item->qty, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if ($item->options->features)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($item->options->features as $feature)
                                                        <span
                                                            class="text-xs bg-gray-700 text-gray-300 px-2.5 py-1 rounded-full border border-gray-600">
                                                            {{ $feature }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="mt-2 flex items-center justify-between">
                                                <p class="text-sm text-gray-400">Cantidad: {{ $item->qty }}</p>
                                                @if (!empty($item->options['offer']))
                                                    <span
                                                        class="text-xs bg-green-900/30 text-green-300 px-2 py-1 rounded-full">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $item->options['offer']['discount_percent'] ?? 0 }}% OFF
                                                    </span>
                                                @endif
                                            </div>
                                            {{-- Mensaje de stock insuficiente --}}
                                            @php
                                                // Obtener la variante actual desde la base de datos
                                                $currentVariant = \App\Models\Variant::find(
                                                    $item->options['variant_id'],
                                                );
                                                $currentStock = $currentVariant ? $currentVariant->stock : 0;
                                            @endphp

                                            @if ($item->qty > $currentStock)
                                                <div class="text-red-400 text-sm font-medium mb-2 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                    Stock insuficiente (disponible: {{ $currentStock }})
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Resumen de precios --}}
                        <div class="space-y-3 border-t border-gray-700 pt-5">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="font-medium text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>

                            @if ($discount > 0)
                                <div class="flex justify-between text-green-400">
                                    <span>Descuentos</span>
                                    <span class="font-medium">-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="text-gray-400">Envío</span>
                                <span class="font-medium text-white">${{ number_format($shipping, 2) }}</span>
                            </div>

                            {{-- @if ($tax > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Impuestos</span>
                                    <span class="font-medium text-white">${{ number_format($tax, 2) }}</span>
                                </div>
                            @endif --}}

                            <div class="flex justify-between text-lg font-bold pt-3 mt-3 border-t border-gray-700">
                                <span class="text-white">Total</span>
                                <span class="text-indigo-400">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        {{-- Botón de pago --}}
                        <form action="{{ route('payment.create') }}" method="POST" class="mt-8">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center space-x-3">
                                <i class="fas fa-lock text-lg"></i>
                                <span>Pagar ${{ number_format($total, 2) }}</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </form>

                        {{-- Garantías y seguridad --}}
                        <div class="mt-8 pt-5 border-t border-gray-700">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div class="p-3 rounded-lg hover:bg-gray-700/50 transition-colors">
                                    <div
                                        class="bg-indigo-900/30 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 text-indigo-400">
                                        <i class="fas fa-shield-alt text-xl"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-300">Pago seguro</p>
                                </div>
                                <div class="p-3 rounded-lg hover:bg-gray-700/50 transition-colors">
                                    <div
                                        class="bg-green-900/30 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 text-green-400">
                                        <i class="fas fa-undo text-xl"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-300">Devoluciones</p>
                                </div>
                                <div class="p-3 rounded-lg hover:bg-gray-700/50 transition-colors">
                                    <div
                                        class="bg-blue-900/30 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 text-blue-400">
                                        <i class="fas fa-headset text-xl"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-300">Soporte 24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-container>
    </div>
</x-app-layout>
