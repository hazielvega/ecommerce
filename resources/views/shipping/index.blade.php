<x-app-layout>
    <x-container class="py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Columna de Dirección de Envío --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-truck mr-3 text-amber-400"></i>
                            Información de Envío
                        </h2>
                    </div>
                    <div class="p-6">
                        @livewire('shipping-addresses')
                    </div>
                </div>
            </div>

            {{-- Columna de Resumen --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden sticky top-4">
                    {{-- Encabezado --}}
                    <div class="px-6 py-4 bg-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-shopping-bag mr-3 text-amber-400"></i>
                            Tu Pedido ({{ Cart::instance('shopping')->count() }})
                        </h2>
                        <a href="{{ route('cart.index') }}"
                            class="text-sm text-amber-300 hover:text-amber-200 transition-colors flex items-center">
                            <i class="fas fa-edit mr-1"></i>
                            Editar
                        </a>
                    </div>

                    {{-- Lista de Productos --}}
                    <div class="bg-gray-900/50 p-6">
                        <ul class="divide-y divide-gray-700">
                            @foreach (Cart::content() as $item)
                                <li class="py-4">
                                    <div class="flex items-start space-x-4">
                                        {{-- Imagen --}}
                                        <img src="{{ $item->options->image }}"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-600"
                                            alt="{{ $item->name }}">

                                        {{-- Detalles --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-white truncate uppercase">
                                                {{ $item->name }}
                                            </p>

                                            @if ($item->options->features)
                                                <div class="mt-1 flex flex-wrap gap-1">
                                                    @foreach ($item->options->features as $feature)
                                                        <span
                                                            class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded">
                                                            {{ $feature }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="mt-2 flex justify-between items-center">
                                                <span class="text-sm text-gray-400">x{{ $item->qty }}</span>
                                                <span class="text-sm font-medium text-white">
                                                    ${{ number_format($item->price * $item->qty, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Costos Adicionales --}}
                        <div class="space-y-3 mt-6">
                            <div class="flex justify-between text-gray-300">
                                <span>Subtotal</span>
                                <span>${{ number_format(Cart::subtotal(), 2) }}</span>
                            </div>

                            <div class="flex justify-between text-gray-300">
                                <span>Envío</span>
                                <span>${{ number_format($shipping, 2) }}</span>
                            </div>

                            @if ($discount > 0)
                                <div class="flex justify-between text-green-400">
                                    <span>Descuento</span>
                                    <span>-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Total --}}
                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-white">Total</span>
                                <span class="text-xl font-bold text-amber-400">
                                    ${{ number_format($total, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Métodos de Pago --}}
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-gray-400 mb-3">Métodos de pago aceptados</h3>
                            <div class="flex space-x-3">
                                <div class="p-2 bg-gray-700 rounded-lg">
                                    <i class="fab fa-cc-visa text-2xl text-blue-500"></i>
                                </div>
                                <div class="p-2 bg-gray-700 rounded-lg">
                                    <i class="fab fa-cc-mastercard text-2xl text-red-500"></i>
                                </div>
                                <div class="p-2 bg-gray-700 rounded-lg">
                                    <i class="fab fa-cc-paypal text-2xl text-blue-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
