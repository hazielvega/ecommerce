<x-app-layout>
    {{-- CSS para Swiper --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            .swiper-slide {
                opacity: 0.5;
                transition: opacity 0.3s ease;
            }

            .swiper-slide-active {
                opacity: 1;
            }

            .swiper-pagination-bullet {
                @apply w-3 h-3 bg-white/30 transition-all duration-300;
            }

            .swiper-pagination-bullet-active {
                @apply w-6 bg-[#CBFF99] rounded-lg;
            }

            .swiper-button-next,
            .swiper-button-prev {
                @apply text-[#CBFF99] hover:text-white bg-black/20 hover:bg-black/40 backdrop-blur-sm w-12 h-12 rounded-full transition-all duration-300;
            }

            .swiper-button-next::after,
            .swiper-button-prev::after {
                @apply text-xl font-bold;
            }
        </style>
    @endpush

    {{-- Hero Slider --}}
    <section class="swiper mb-16 group">
        <div class="swiper-wrapper">
            @foreach ($covers as $cover)
                <div class="swiper-slide">
                    <div class="relative h-[800px] w-full">
                        <img src="{{ $cover->image }}" class="absolute inset-0 w-full h-full object-cover object-center"
                            alt="Portada promocional" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination !bottom-8"></div>
        <div class="swiper-button-next !right-8"></div>
        <div class="swiper-button-prev !left-8"></div>
    </section>

    {{-- Product Grid --}}
    <x-container>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="mb-12 text-center">
                <h1 class="text-4xl font-bold text-[#CBFF99] mb-3">Últimos lanzamientos</h1>
                <p class="text-gray-300 max-w-2xl mx-auto">Descubre nuestras novedades cuidadosamente seleccionadas para
                    ti</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($lastProducts as $product)
                    <article
                        class="group relative bg-gray-700 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        {{-- Badge --}}
                        @if ($product->activeOffer())
                            <div class="absolute top-4 left-4 z-10">
                                <span
                                    class="px-3 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center">
                                    -{{ $product->activeOffer()->discount_percentage }}% OFF
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </div>
                        @endif

                        {{-- Product Image --}}
                        <figure class="relative aspect-square w-full h-80 overflow-hidden">
                            <img src="{{ $product->image }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $product->name }}" loading="lazy">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </figure>

                        {{-- Product Info --}}
                        <div class="p-5">
                            <h2 class="text-lg font-semibold text-white mb-2 line-clamp-2">
                                {{ $product->name }}
                            </h2>

                            <div class="flex flex-col items-start mt-4">
                                @if ($product->activeOffer())
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-gray-400 text-sm line-through">
                                            ${{ number_format($product->sale_price, 2) }}
                                        </span>
                                        <span class="text-xl font-bold text-[#CBFF99]">
                                            ${{ number_format($product->sale_price * (1 - $product->activeOffer()->discount_percentage / 100), 2) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xl font-bold text-[#CBFF99]">
                                        ${{ number_format($product->sale_price, 2) }}
                                    </span>
                                @endif

                                {{-- Stock Status --}}
                                @if ($product->totalStock() <= 0)
                                    <span
                                        class="mt-3 px-3 py-1 text-xs font-medium text-white bg-gray-700 rounded-full">
                                        Agotado
                                    </span>
                                @elseif($product->totalStock() <= 5)
                                    <span
                                        class="mt-3 px-3 py-1 text-xs font-medium text-white bg-yellow-600 rounded-full animate-pulse">
                                        Últimas unidades
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Quick View Button --}}
                        <div
                            class="absolute bottom-20 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('products.show', $product) }}"
                                class="px-6 py-2 bg-[#CBFF99] hover:bg-white text-gray-900 font-medium rounded-full shadow-lg transition-colors duration-300 flex items-center">
                                Ver producto
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </x-container>

    {{-- JS para Swiper --}}
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
