<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- Carrusel de Imágenes --}}
    <div class="relative">
        <div x-data="{ activeImage: '{{ $product->image }}' }" class="space-y-4">
            {{-- Imagen Principal --}}
            <div class="relative rounded-xl overflow-hidden shadow-lg bg-gray-800 aspect-square">
                <img :src="activeImage" class="w-full h-full object-cover" alt="{{ $product->name }}">
                
                {{-- Badge de Oferta --}}
                @if($product->offers->isNotEmpty())
                <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                    -{{ $product->offers->first()->discount_percentage }}%
                </div>
                @endif
            </div>

            {{-- Miniaturas --}}
            <div class="flex space-x-3">
                @foreach (json_decode($product->image_path, true) as $image)
                    <img src="{{ Storage::url($image) }}" 
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer border-2 hover:border-amber-400 transition-all"
                         :class="{ 'border-amber-400': activeImage === '{{ Storage::url($image) }}' }"
                         @click="activeImage = '{{ Storage::url($image) }}'">
                @endforeach
            </div>
        </div>
    </div>

    {{-- Detalles del Producto --}}
    <div class="space-y-6">
        <!-- Título y Marca -->
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-wide">{{ $product->name }}</h1>
            @if($product->brand)
            <p class="text-gray-400 mt-1">Marca: <span class="text-amber-300">{{ $product->brand->name }}</span></p>
            @endif
        </div>

        <!-- Precio y Stock -->
        <div class="flex items-center justify-between bg-gray-800/50 p-4 rounded-lg">
            <div class="flex items-end space-x-3">
                @if($product->offers->isNotEmpty())
                    <p class="text-4xl font-bold text-amber-200">${{ $variant->sale_price * (1 - $product->offers->first()->discount_percentage / 100) }}</p>
                    <p class="text-xl text-gray-400 line-through">${{ $variant->sale_price }}</p>
                @else
                    <p class="text-4xl font-bold text-amber-200">${{ $variant->sale_price }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-400">Disponibles:</span>
                <span class="text-lg font-semibold {{ $stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $stock }}
                </span>
            </div>
        </div>

        <!-- Selector de Cantidad -->
        <div x-data="{ quantity: @entangle('quantity'), stock: @entangle('stock') }" class="bg-gray-800/50 p-4 rounded-lg">
            <p class="text-gray-300 mb-3">Cantidad:</p>
            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    x-on:click="quantity = Math.max(1, quantity - 1)" 
                    x-bind:disabled="quantity <= 1"
                    :class="{ 'opacity-50 cursor-not-allowed': quantity <= 1 }">
                    -
                </button>

                <span class="text-xl text-white px-4 py-2 bg-gray-700 rounded-lg min-w-[50px] text-center" 
                      x-text="quantity"></span>

                <button class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    x-on:click="quantity = Math.min(stock, quantity + 1)" 
                    x-bind:disabled="quantity >= stock"
                    :class="{ 'opacity-50 cursor-not-allowed': quantity >= stock }">
                    +
                </button>
            </div>
        </div>

        <!-- Variantes -->
        <div class="space-y-6">
            @foreach ($product->options() as $option)
                <div class="bg-gray-800/50 p-4 rounded-lg">
                    <p class="text-lg font-semibold text-gray-300 mb-3">{{ $option->name }}</p>
                    <ul class="flex flex-wrap gap-3">
                        @foreach ($option->features()->whereHas('products', fn($q) => $q->where('products.id', $product->id))->get() as $feature)
                            <li>
                                @switch($option->type)
                                    @case(1) {{-- Tipo texto --}}
                                        <button
                                            class="px-4 py-2 border uppercase rounded-lg text-sm font-semibold transition-all
                                                  {{ $selectedFeatures[$option->id] == $feature['id'] ? 
                                                     'bg-amber-500 text-black border-amber-500' : 
                                                     'border-gray-600 text-gray-300 hover:bg-gray-700 hover:border-gray-500' }}"
                                            wire:click="$set('selectedFeatures.{{ $option->id }}', {{ $feature['id'] }})">
                                            {{ $feature['value'] }}
                                        </button>
                                    @break

                                    @case(2) {{-- Tipo color --}}
                                        <div class="p-1 border-2 rounded-full transition-all 
                                                   {{ $selectedFeatures[$option->id] == $feature['id'] ? 
                                                      'border-amber-500 ring-2 ring-amber-300' : 
                                                      'border-gray-600 hover:border-gray-400' }}"
                                            wire:click="$set('selectedFeatures.{{ $option->id }}', {{ $feature['id'] }})">
                                            <button class="w-10 h-10 rounded-full border border-gray-300 shadow-md"
                                                style="background-color: {{ $feature['value'] }}"
                                                title="{{ $feature['value'] }}"></button>
                                        </div>
                                    @break
                                @endswitch
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <!-- Acciones -->
        <div class="space-y-4">
            <button
                class="w-full px-6 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold rounded-lg shadow-lg 
                       hover:from-amber-400 hover:to-amber-500 transition-all transform hover:scale-[1.01]
                       disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed"
                wire:click="add_to_cart" 
                wire:loading.attr="disabled" 
                x-bind:disabled="stock == 0">
                <span wire:loading.remove>Agregar al carrito</span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin mr-2"></i> Procesando...
                </span>
            </button>

            <div class="flex items-center space-x-4 bg-gray-800/50 p-4 rounded-lg">
                <i class="fa-solid fa-truck text-2xl text-amber-300"></i>
                <div>
                    <p class="text-gray-300 font-medium">Envíos a domicilio</p>
                    <p class="text-gray-400 text-sm">Recíbelo en 2-3 días hábiles</p>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="bg-gray-800/50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-300 mb-2">Descripción</h3>
            <p class="text-gray-400 leading-relaxed">{{ $product->description }}</p>
        </div>
        
        <!-- Oferta Especial (si aplica) -->
        @if($product->offers->isNotEmpty())
        <div class="bg-gradient-to-r from-red-500/20 to-amber-500/20 p-4 rounded-lg border border-amber-400/30">
            <div class="flex items-center space-x-3">
                <div class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">OFERTA</div>
                <p class="text-amber-300 font-medium">
                    {{ $product->offers->first()->name }} - 
                    {{ $product->offers->first()->discount_percentage }}% de descuento
                </p>
            </div>
            @if($product->offers->first()->description)
                <p class="text-gray-300 mt-2 text-sm">{{ $product->offers->first()->description }}</p>
            @endif
            <p class="text-gray-400 text-xs mt-2">
                Válido hasta {{ $product->offers->first()->end_date}}
            </p>
        </div>
        @endif
    </div>
</div>