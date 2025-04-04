<div>
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
        {{-- Columna izquierda --}}
        <div class="lg:col-span-5">
            {{-- Encabezado --}}
            <div class="flex justify-between mb-2">
                <h1 class="text-lg text-white">
                    Carrito de Compras ({{ Cart::count() }} productos)
                </h1>

                {{-- Botón para borrar todo --}}
                <button class="font-semibold text-gray-300 underline hover:no-underline" wire:click="clear()">
                    Borrar todo
                </button>
            </div>

            {{-- Lista de productos --}}
            <div class="card bg-[#7d8567] text-white">

                <ul class="space-y-2">
                    @forelse (Cart::content() as $item)
                        <li
                            class="lg:flex lg:items-center py-3 bg-white text-black rounded-lg {{ $item->qty > $item->options['stock'] ? 'text-red-400' : '' }}">
                            <div class="flex ">
                                {{-- Imagen --}}
                                <img class="w-20 h-20 object-cover object-center mr-4 ml-4"
                                    src="{{ $item->options->image }}" alt="">

                                {{-- Nombre del producto  y
                                boton para eliminar --}}
                                <div class="lg:w-80">

                                    {{-- Si la cantidad a agregar supera el stock --}}
                                    @if ($item->qty > $item->options['stock'])
                                        <p class="text-sm font-bold truncate">
                                            Stock insuficiente
                                        </p>
                                    @endif

                                    <p class="text-sm font-bold truncate">
                                        <a href="{{ route('products.show', $item->id) }}" class="uppercase">
                                            {{ $item->name }}
                                        </a>
                                        {{-- Caracteristicas del producto --}}
                                        <p>
                                            @foreach ($item->options->features as $feature)
                                                {{ $feature }}
                                            @endforeach
                                        </p>

                                    </p>
                                    {{-- Botón para eliminar --}}
                                    <button class="text-xs text-red-500 bg-slate-200 rounded px-2.5 py-0.5 mt-4 mb-2"
                                        wire:click="remove('{{ $item->rowId }}')">
                                        <i class="fa-solid fa-trash"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </div>


                            <div class="flex-auto ml-2 mt-1">
                                {{-- Precio --}}
                                <div>
                                    <label class="font-semibold">
                                        Precio
                                    </label>
                                    <p>
                                        ${{ $item->price }}
                                    </p>
                                </div>

                                {{-- Cantidad --}}
                                <div class="space-x-3">
                                    <label class="font-semibold">
                                        Cantidad:
                                    </label>
                                    {{-- Botónes para seleccionar cantidad a comprar --}}
                                    <button class="btn btn-gray" wire:click="decrease('{{ $item->rowId }}')">
                                        -
                                    </button>

                                    <span class=" inline-block w-2 text-center">
                                        {{ $item->qty }}
                                    </span>

                                    <button class="btn btn-gray" wire:click="increase('{{ $item->rowId }}')"
                                        @disabled($item->qty >= $item->options['stock'])>
                                        +
                                    </button>
                                </div>
                            </div>
                        </li>
                    @empty
                        <p class="text-center">
                            No hay productos en el carrito
                        </p>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Columna derecha --}}
        <div class="lg:col-span-2">
            <div class="card bg-[#b7c591]">
                {{-- Totales --}}
                <div class="flex justify-between font-semibold mb-2">
                    <p>
                        Total
                    </p>

                    <p>
                        ${{ $this->subtotal }}
                    </p>
                </div>

                {{-- Botón para comprar --}}
                <div>
                    <button wire:click="validateBeforeCheckout" class="w-full">
                        <span class="block w-full text-center btn btn-indigo mt-4">Continuar compra</span>
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>
