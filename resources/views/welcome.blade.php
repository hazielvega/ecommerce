<x-app-layout>

    {{-- CSS para usar swiper --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush

    <!-- Slider de portadas-->
    <div class="swiper mb-12">
        <div class="swiper-wrapper">
            @foreach ($covers as $cover)
                <div class="swiper-slide flex justify-center items-center">
                    <img src="{{ $cover->image }}" class="w-full h-[400px] md:h-[500px] lg:h-[600px] object-contain"
                        alt="">
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>


    {{-- Productos --}}
    <x-container>
        <h1 class="text-2xl font-bold mb-4 px-4 text-[#CBFF99]">
            Últimos lanzamientos
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-0">
            @foreach ($lastProducts as $product)
                <a href="{{ route('products.show', $product) }}"
                    class="group relative flex flex-col bg-gray-200 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">

                    {{-- Badge de Oferta --}}
                    @if ($product->activeOffer())
                        <div class="absolute top-2 left-2 z-10">
                            <span
                                class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-md flex items-center">
                                -{{ $product->activeOffer()->discount_percentage }}%
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </div>
                    @endif

                    {{-- Imagen del Producto --}}
                    <div class="relative h-80 w-full overflow-hidden">
                        <img src="{{ $product->image }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            alt="{{ $product->name }}" loading="lazy">
                    </div>

                    {{-- Contenido --}}
                    <div class="p-4 flex flex-col flex-grow items-center">
                        <h3 class="text-gray-800 font-medium text-sm mb-2 line-clamp-2">{{ $product->name }}</h3>

                        <div class="mt-auto">
                            @if ($product->activeOffer())
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="text-gray-400 text-sm line-through">
                                        ${{ number_format($product->sale_price, 2) }}
                                    </span>
                                    <span class="text-lg font-bold text-red-600">
                                        ${{ number_format($product->sale_price * (1 - $product->activeOffer()->discount_percentage / 100), 2) }}
                                    </span>
                                </div>
                            @else
                                <span class="text-lg font-semibold text-indigo-600">
                                    ${{ number_format($product->sale_price, 2) }}
                                </span>
                            @endif
                        </div>

                        {{-- Stock --}}
                        @if ($product->totalStock() <= 0)
                            <span
                                class="inline-block mt-2 px-2 py-1 text-xs text-white bg-gray-500 rounded-full">Agotado</span>
                        @elseif($product->totalStock() <= 5)
                            <span
                                class="inline-block mt-2 px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Últimas
                                unidades</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </x-container>




    {{-- JS para usar swiper --}}
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script>
            const swiper = new Swiper('.swiper', {
                // Optional parameters
                loop: true,

                // Tiempo para mostrar cada portada
                autoplay: {
                    delay: 4000,
                },

                // If we need pagination
                pagination: {
                    el: '.swiper-pagination',
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        </script>
    @endpush
</x-app-layout>
