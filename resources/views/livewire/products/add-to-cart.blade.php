<div class="grid grid-cols-1 md:grid-cols-2 gap-6">


    {{-- Carrusel de Imágenes --}}
    <div class="relative">
        {{-- @dump(json_decode($product->image_path, true)[0]) --}}
        <div x-data="{ activeImage: '{{ $product->image }}' }">
            {{-- Imagen Principal --}}
            <img :src="activeImage" class="w-full h-auto rounded-lg shadow-md">

            {{-- Miniaturas --}}
            <div class="flex space-x-2 mt-4">
                @foreach (json_decode($product->image_path, true) as $image)
                    <img src="{{ Storage::url($image) }}" class="w-16 h-16 object-cover rounded-lg cursor-pointer"
                        @click="activeImage = '{{ Storage::url($image) }}'">
                @endforeach
            </div>
        </div>
    </div>

    {{-- Detalles --}}
    <div>
        <!-- Título -->
        <h1 class="text-3xl font-bold text-white uppercase mb-4">
            {{ $product->name }}
        </h1>

        <!-- Precio y Stock -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-4xl font-bold text-amber-200">${{ $variant->sale_price }}</p>
            <p class="text-lg text-gray-400">Stock: <span class="text-white">{{ $stock }}</span></p>
        </div>

        <!-- Botones para seleccionar cantidad -->
        <div x-data="{ quantity: @entangle('quantity'), stock: @entangle('stock') }" class="flex items-center space-x-4 mb-6">
            <button class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600"
                x-on:click="quantity = Math.max(1, quantity - 1)" x-bind:disabled="quantity <= 1">
                -
            </button>

            <span class="text-xl text-white" x-text="quantity"></span>

            <button class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600"
                x-on:click="quantity = Math.min(stock, quantity + 1)" x-bind:disabled="quantity >= stock">
                +
            </button>
        </div>


        <!-- Variantes -->
        <div class="mb-6">
            @foreach ($product->options() as $option)
                <div class="mb-4">
                    <p class="text-lg font-semibold text-gray-300 mb-2">{{ $option->name }}</p>
                    <ul class="flex flex-wrap gap-3">
                        @foreach ($option->features()->whereHas('products', fn($q) => $q->where('products.id', $product->id))->get() as $feature)
                            <li>
                                @switch($option->type)
                                    @case(1)
                                        <!-- Tipo texto -->
                                        <button
                                            class="px-4 py-2 border uppercase rounded-lg text-sm font-semibold {{ $selectedFeatures[$option->id] == $feature['id'] ? 'bg-amber-500 text-black' : 'border-gray-400 text-gray-300 hover:bg-gray-700' }}"
                                            wire:click="$set('selectedFeatures.{{ $option->id }}', {{ $feature['id'] }})">
                                            {{ $feature['value'] }}
                                        </button>
                                    @break

                                    @case(2)
                                        <!-- Tipo color -->
                                        <div class="p-1 border-2 rounded-lg {{ $selectedFeatures[$option->id] == $feature['id'] ? 'border-amber-500' : 'border-gray-400' }}"
                                            wire:click="$set('selectedFeatures.{{ $option->id }}', {{ $feature['id'] }})">
                                            <button class="w-10 h-10 rounded-lg border border-gray-300"
                                                style="background-color: {{ $feature['value'] }}"></button>
                                        </div>
                                    @break
                                @endswitch
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <!-- Botón para agregar al carrito -->
        <div class="mb-6">
            <button
                class="w-full px-4 py-3 bg-indigo-500 text-white font-semibold rounded-lg shadow-lg hover:bg-indigo-400 disabled:bg-gray-600 disabled:text-gray-400"
                wire:click="add_to_cart" wire:loading.attr="disabled" x-bind:disabled="stock == 0">
                Agregar al carrito
            </button>
        </div>

        <!-- Envíos a domicilio -->
        <div class="flex items-center space-x-3 mb-6">
            <i class="fa-solid fa-truck text-2xl text-amber-300"></i>
            <p class="text-gray-400">Envíos a domicilio disponibles</p>
        </div>

        <!-- Descripción del producto -->
        <div class="text-gray-300 text-sm">
            <p class="font-semibold">Descripción:</p>
            <p class="mt-2">{{ $product->description }}</p>
        </div>
    </div>


</div>
