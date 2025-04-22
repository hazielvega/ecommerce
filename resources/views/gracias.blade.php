<x-app-layout>
    <div class="bg-gray-900 min-h-screen py-12">
        <x-container>
            <div class="flex flex-col items-center">
                {{-- Tarjeta de confirmación principal --}}
                <div
                    class="w-full max-w-2xl bg-gradient-to-br from-green-800 to-green-900 p-8 rounded-2xl shadow-xl border border-green-700 text-center">
                    {{-- Icono de confirmación --}}
                    <div
                        class="mx-auto w-20 h-20 bg-green-700 rounded-full flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-check-circle text-4xl text-white"></i>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        ¡Gracias por tu compra!
                    </h1>

                    <p class="text-xl text-green-200 mb-6">
                        Tu pedido ha sido procesado con éxito
                    </p>

                    {{-- Detalles del envío --}}
                    <div class="bg-green-900/50 p-4 rounded-lg border border-green-700 mb-6 text-left">
                        <p class="text-green-300 mb-2">
                            <i class="fas fa-envelope mr-2"></i>
                            Hemos enviado los detalles a tu correo electrónico
                        </p>
                        <p class="text-green-300">
                            <i class="fas fa-clock mr-2"></i>
                            Recibirás un correo con el número de seguimiento cuando despachemos tu pedido
                        </p>
                    </div>

                    {{-- Botón de acción --}}
                    @if (auth()->check())
                        <a href="{{ route('orders.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-white text-green-900 font-bold rounded-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            Ver mis pedidos
                        </a>
                    @endif

                    {{-- Continuar comprando --}}
                    <div class="mt-8">
                        <a href="{{ route('welcome.index') }}" class="text-green-300 hover:text-white transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Seguir comprando
                        </a>
                    </div>
                </div>

                {{-- Detalles de pago Niubiz --}}
                @if (session('niubiz'))
                    @php
                        $response = session('niubiz')['response'];
                    @endphp

                    <div class="w-full max-w-2xl mt-8 bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <h2 class="text-xl font-bold text-white">
                                Detalles de la transacción
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-300">
                            {{-- Columna 1 --}}
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Estado del pago</p>
                                    <p class="font-medium text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $response['dataMap']['ACTION_DESCRIPTION'] }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-400">Número de pedido</p>
                                    <p class="font-medium">{{ $response['order']['purchaseNumber'] }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-400">Método de pago</p>
                                    <p class="font-medium">
                                        {{ $response['dataMap']['BRAND'] }}
                                        (****{{ substr($response['dataMap']['CARD'], -4) }})
                                    </p>
                                </div>
                            </div>

                            {{-- Columna 2 --}}
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Fecha y hora</p>
                                    <p class="font-medium">
                                        {{ now()->createFromFormat('ymdHis', $response['dataMap']['TRANSACTION_DATE'])->format('d/m/Y H:i:s') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-400">Importe total</p>
                                    <p class="font-medium text-xl text-white">
                                        {{ $response['order']['amount'] }} {{ $response['order']['currency'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Garantías --}}
                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <div class="flex flex-wrap justify-center gap-4">
                                <div class="flex items-center text-sm text-gray-400">
                                    <i class="fas fa-shield-alt text-green-400 mr-2"></i>
                                    Pago 100% seguro
                                </div>
                                <div class="flex items-center text-sm text-gray-400">
                                    <i class="fas fa-lock text-blue-400 mr-2"></i>
                                    Datos encriptados
                                </div>
                                <div class="flex items-center text-sm text-gray-400">
                                    <i class="fas fa-headset text-purple-400 mr-2"></i>
                                    Soporte 24/7
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-container>
    </div>
</x-app-layout>
