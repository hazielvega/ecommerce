<x-app-layout>
    <x-container>
        <div class="text-gray-800" x-data="{ pago: 1 }">
            <div
                class="grid grid-cols-1 lg:grid-cols-2 mt-6 rounded-xl overflow-hidden bg-gradient-to-br from-amber-100 to-amber-50 shadow-lg m-2 gap-0.5">

                {{-- Sección de Información y Pago --}}
                <div class="col-span-1 bg-white p-1">
                    <div class="py-8 px-6 lg:pr-8 lg:pl-8 space-y-6">

                        <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-receipt mr-3 text-amber-500"></i>
                            Resumen de compra
                        </h1>

                        {{-- Información del destinatario --}}
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-user-circle mr-2 text-amber-500"></i>
                                    Destinatario
                                </h2>
                                <button class="text-sm text-amber-600 hover:text-amber-700">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>
                            </div>
                            @if ($receiver)
                                <div class="space-y-2 text-gray-600">
                                    <p class="flex items-center">
                                        <i class="fas fa-user mr-2 w-5 text-amber-400"></i>
                                        <span class="font-medium">{{ $receiver->name }}
                                            {{ $receiver->last_name }}</span>
                                    </p>
                                    <p class="flex items-center">
                                        <i class="fas fa-id-card mr-2 w-5 text-amber-400"></i>
                                        {{ $receiver->document_number }}
                                    </p>
                                    <p class="flex items-center">
                                        <i class="fas fa-envelope mr-2 w-5 text-amber-400"></i>
                                        {{ $receiver->email }}
                                    </p>
                                    <p class="flex items-center">
                                        <i class="fas fa-phone mr-2 w-5 text-amber-400"></i>
                                        {{ $receiver->phone }}
                                    </p>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No hay destinatario seleccionado.</p>
                            @endif
                        </div>

                        {{-- Dirección de envío --}}
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-truck mr-2 text-amber-500"></i>
                                    Dirección de Envío
                                </h2>
                                <button class="text-sm text-amber-600 hover:text-amber-700">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>
                            </div>
                            @if ($shipping_address)
                                <div class="space-y-2 text-gray-600">
                                    <p>{{ $shipping_address->calle }} {{ $shipping_address->numero }}</p>
                                    <p>{{ $shipping_address->ciudad }}, {{ $shipping_address->provincia }}</p>
                                    <p>Código Postal: {{ $shipping_address->codigo_postal }}</p>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No hay dirección de envío seleccionada.</p>
                            @endif
                        </div>

                        {{-- Dirección de facturación --}}
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-file-invoice mr-2 text-amber-500"></i>
                                    Dirección de Facturación
                                </h2>
                                <button class="text-sm text-amber-600 hover:text-amber-700">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>
                            </div>
                            @if ($billing_address)
                                <div class="space-y-2 text-gray-600">
                                    <p>{{ $billing_address->calle }} {{ $billing_address->numero }}</p>
                                    <p>{{ $billing_address->ciudad }}, {{ $billing_address->provincia }}</p>
                                    <p>Código Postal: {{ $billing_address->codigo_postal }}</p>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No hay dirección de facturación seleccionada.</p>
                            @endif
                        </div>

                        {{-- Métodos de pago --}}
                        <div class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                            <h2
                                class="text-lg font-semibold text-gray-700 p-5 border-b border-gray-100 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-amber-500"></i>
                                Método de Pago
                            </h2>

                            <ul>
                                <li class="border-b border-gray-100 last:border-0">
                                    <label
                                        class="p-5 flex items-start cursor-pointer hover:bg-amber-50 transition-colors">
                                        <input type="radio" x-model="pago" value="1" class="mt-1">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-medium">Tarjeta de crédito/débito</p>
                                                <div class="flex space-x-2">
                                                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visa/visa-original.svg"
                                                        class="h-6" alt="Visa">
                                                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mastercard/mastercard-original.svg"
                                                        class="h-6" alt="Mastercard">
                                                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/americanexpress/americanexpress-original.svg"
                                                        class="h-6" alt="Amex">
                                                </div>
                                            </div>

                                            <div class="mt-3 bg-amber-50 p-4 rounded-lg text-center" x-show="pago == 1">
                                                <div class="flex items-center justify-center space-x-2 text-amber-600">
                                                    <i class="fas fa-lock text-xl"></i>
                                                    <p class="text-sm">
                                                        Pago seguro en ventana externa con encriptación SSL
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Resumen de compra --}}
                <div class="col-span-1 bg-white p-1">
                    <div class="py-8 px-6 lg:pl-8 lg:pr-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-shopping-bag mr-3 text-amber-500"></i>
                            Tus Productos
                        </h2>

                        {{-- Lista de productos --}}
                        <ul class="space-y-4 mb-6">
                            @foreach ($content as $item)
                                <li
                                    class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex-shrink-0">
                                        <img class="h-20 w-20 object-cover rounded-lg border border-gray-200"
                                            src="{{ $item->options['image'] }}" alt="">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800">{{ $item->name }}</p>
                                        @if (count($item->options->features) > 0)
                                            <p class="text-sm text-gray-500 mt-1">
                                                @foreach ($item->options->features as $feature)
                                                    <span
                                                        class="inline-block bg-gray-100 rounded-full px-2 py-0.5 text-xs mr-1 mb-1">
                                                        {{ $feature }}
                                                    </span>
                                                @endforeach
                                            </p>
                                        @endif
                                        <p class="text-gray-700 font-semibold mt-1">
                                            ${{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <div class="flex-shrink-0 text-gray-500 text-sm">
                                        Cantidad: {{ $item->qty }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Resumen de precios --}}
                        <div class="bg-gray-50 rounded-xl p-5 mb-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <p class="text-gray-600">Subtotal:</p>
                                    <p class="font-medium">${{ number_format($subtotal, 2) }}</p>
                                </div>

                                <div class="flex justify-between">
                                    <p class="text-gray-600 flex items-center">
                                        <i class="fas fa-truck mr-2 text-amber-500"></i>
                                        Envío:
                                    </p>
                                    <p class="font-medium">${{ number_format($shipping, 2) }}</p>
                                </div>

                                <hr class="border-gray-200 my-2">

                                <div class="flex justify-between">
                                    <p class="text-lg font-bold text-gray-800">Total:</p>
                                    <p class="text-lg font-bold text-amber-600">${{ number_format($total, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Botón para confirmar --}}
                        <div class="mb-6">
                            <button
                                class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center space-x-2"
                                onclick="VisanetCheckout.open()">
                                <i class="fas fa-lock"></i>
                                <span>PAGAR AHORA</span>
                            </button>

                            <p class="text-xs text-gray-500 mt-3 text-center">
                                <i class="fas fa-shield-alt mr-1 text-amber-500"></i>
                                Tu información está protegida con encriptación SSL
                            </p>
                        </div>

                        {{-- Garantías --}}
                        <div class="grid grid-cols-2 gap-3 text-center">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <i class="fas fa-undo-alt text-amber-500 mb-1"></i>
                                <p class="text-xs font-medium text-gray-600">Devoluciones fáciles</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <i class="fas fa-shield-alt text-amber-500 mb-1"></i>
                                <p class="text-xs font-medium text-gray-600">Compra protegida</p>
                            </div>
                        </div>

                        {{-- Alerta de pago denegado --}}
                        @if (session('niubiz'))
                            @php
                                $niubiz = session('niubiz');
                                $response = $niubiz['response'];
                                $purchaseNumber = $niubiz['purchaseNumber'];
                            @endphp

                            @isset($response['data'])
                                <div class="mt-6 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-100"
                                    role="alert">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                                        <h3 class="font-bold">Error en el pago</h3>
                                    </div>
                                    <p class="mb-3">
                                        {{ $response['data']['ACTION_DESCRIPTION'] }}
                                    </p>
                                    <div class="space-y-1 text-xs">
                                        <p><b>Número de pedido:</b> {{ $purchaseNumber }}</p>
                                        <p><b>Fecha:</b>
                                            {{ now()->createFromFormat('ymdHis', $response['data']['TRANSACTION_DATE'])->format('d/m/Y H:i:s') }}
                                        </p>
                                        @isset($response['data']['CARD'])
                                            <p><b>Tarjeta:</b> {{ $response['data']['CARD'] }}
                                                ({{ $response['data']['BRAND'] }})</p>
                                        @endisset
                                    </div>
                                    <button class="mt-3 text-sm text-red-600 hover:text-red-700 font-medium">
                                        <i class="fas fa-sync-alt mr-1"></i> Reintentar pago
                                    </button>
                                </div>
                            @endisset
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-container>

    {{-- Script para procesar el pago --}}
    @push('js')
        <script type="text/javascript" src="{{ config('services.niubiz.url_js') }}"></script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                let purchasenumber = Math.floor(Math.random() * 1000000000);
                let amount = {{ $total }};

                VisanetCheckout.configure({
                    sessiontoken: "{{ $session_token }}",
                    channel: 'web',
                    merchantid: "{{ config('services.niubiz.merchant_id') }}",
                    purchasenumber: purchasenumber,
                    amount: amount,
                    expirationminutes: '20',
                    timeouturl: 'about:blank',
                    merchantlogo: 'img/comercio.png',
                    formbuttoncolor: '#d97706', // Color ámbar-600
                    action: "{{ route('checkout.paid') }}?amount=" + amount + "&purchaseNumber=" +
                        purchasenumber,
                    complete: function(params) {
                        // Manejo personalizado de la respuesta
                        console.log(params);
                    }
                });
            })
        </script>
    @endpush
</x-app-layout>
