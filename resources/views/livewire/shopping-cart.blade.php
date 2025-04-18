<div class="bg-gray-900 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    {{-- @dump(Cart::content()) --}}
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
            {{-- Columna izquierda - Lista de productos --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Encabezado --}}
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-white">
                        Tu Carrito <span class="text-amber-300">({{ Cart::count() }} productos)</span>
                    </h1>
                    <button class="text-gray-300 hover:text-white transition-colors flex items-center"
                        wire:click="clear()">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Vaciar Carrito
                    </button>
                </div>

                {{-- Lista de productos --}}
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <ul class="divide-y divide-gray-700">
                        @forelse (Cart::content() as $item)
                            <li
                                class="p-4 hover:bg-gray-700/50 transition-colors {{ $item->qty > $item->options['stock'] ? 'bg-red-900/20' : '' }}">
                                <div class="flex flex-col md:flex-row gap-4">
                                    {{-- Imagen --}}
                                    <div class="w-24 h-24 flex-shrink-0">
                                        <img class="w-full h-full object-cover rounded-lg"
                                            src="{{ $item->options->image }}" alt="{{ $item->name }}">
                                    </div>

                                    {{-- Información del producto --}}
                                    <div class="flex-grow">
                                        {{-- Nombre y características --}}
                                        <div class="mb-2">
                                            <a href="{{ route('products.show', $item->id) }}"
                                                class="text-lg font-bold text-white hover:text-amber-300 transition-colors uppercase">
                                                {{ $item->name }}
                                            </a>

                                            @if ($item->options->features)
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach ($item->options->features as $feature)
                                                        <span
                                                            class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded">
                                                            {{ $feature }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Mensaje de stock insuficiente --}}
                                        @if ($item->qty > $item->options['stock'])
                                            <div class="text-red-400 text-sm font-medium mb-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Stock insuficiente (disponible: {{ $item->options['stock'] }})
                                            </div>
                                        @endif

                                        {{-- Precio --}}
                                        <div class="mb-3">
                                            @if (!empty($item->options['offer']))
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xl font-bold text-amber-300">
                                                        ${{ number_format($item->price, 2) }}
                                                    </span>
                                                    <span class="text-gray-400 line-through">
                                                        ${{ number_format($item->options['original_price'], 2) }}
                                                    </span>
                                                    <span
                                                        class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                        -{{ $item->options['offer']['discount_percent'] }}%
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-xl font-bold text-white">
                                                    ${{ number_format($item->price, 2) }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Controles --}}
                                        <div class="flex justify-between items-center">
                                            {{-- Cantidad --}}
                                            <div class="flex items-center space-x-3 text-white">
                                                <button
                                                    class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                    wire:click="decrease('{{ $item->rowId }}')"
                                                    wire:loading.attr="disabled" @disabled($item->qty <= 1)>
                                                    -
                                                </button>
                                                <span class="w-8 text-center font-medium">
                                                    {{ $item->qty }}
                                                </span>
                                                <button
                                                    class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                    wire:click="increase('{{ $item->rowId }}')"
                                                    wire:loading.attr="disabled" @disabled($item->qty >= $item->options['stock'])>
                                                    +
                                                </button>

                                                @if ($item->options['stock'] > 0)
                                                    <span class="text-xs text-gray-400 ml-2">Disponible:
                                                        {{ $item->options['stock'] }}</span>
                                                @endif
                                            </div>

                                            {{-- Eliminar --}}
                                            <button
                                                class="text-red-400 hover:text-red-300 transition-colors flex items-center"
                                                wire:click="remove('{{ $item->rowId }}')">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                <span class="text-sm">Eliminar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-8 text-center">
                                <i class="fas fa-shopping-cart text-4xl text-gray-500 mb-4"></i>
                                <p class="text-gray-400 text-lg">Tu carrito está vacío</p>
                                <a href="{{ route('welcome.index') }}"
                                    class="mt-4 inline-block px-6 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                    Comenzar a comprar
                                </a>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Columna derecha - Resumen --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-800 rounded-xl shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-white mb-6">Resumen de compra</h2>

                    {{-- Subtotal --}}
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Subtotal</span>
                        <span class="text-white font-medium">${{ number_format($this->subtotal, 2) }}</span>
                    </div>

                    {{-- Descuentos --}}
                    @if ($this->hasDiscounts())
                        <div class="flex justify-between mb-2 text-green-400">
                            <span>Descuentos</span>
                            <span>-${{ number_format($this->discountTotal(), 2) }}</span>
                        </div>
                    @endif

                    {{-- Total --}}
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700">
                        <span class="text-lg font-bold text-white">Total</span>
                        <span class="text-2xl font-bold text-amber-300">
                            ${{ number_format($this->totalWithDiscounts(), 2) }}
                        </span>
                    </div>

                    {{-- Botón de compra --}}
                    <button wire:click="validateBeforeCheckout"
                        class="w-full mt-6 py-3 bg-gradient-to-r disabled:cursor-not-allowed from-amber-500 to-amber-600 text-white font-bold rounded-lg hover:from-amber-400 hover:to-amber-500 transition-all shadow-lg flex items-center justify-center"
                        @disabled(Cart::count() === 0)>
                        <span wire:loading.remove>Continuar con la compra</span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i> Procesando...
                        </span>
                    </button>

                    {{-- Ofertas aplicadas --}}
                    @if ($this->hasDiscounts())
                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <h3 class="text-sm font-semibold text-amber-300 mb-2">Ofertas aplicadas</h3>
                            <ul class="space-y-2">
                                @foreach ($this->appliedOffers() as $offer)
                                    <li class="flex items-start">
                                        <span
                                            class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full mr-2">-{{ $offer['discount_percent'] }}%</span>
                                        <span class="text-gray-300 text-sm">{{ $offer['name'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
