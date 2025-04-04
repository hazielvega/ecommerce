<div class="mt-10">
    {{-- CSS para usar swiper --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush

    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
        Más productos en {{ $category->name }}
    </h2>
    {{-- @dump($products) --}}
    <!-- Swiper Container -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($products as $product)
                <div class="swiper-slide">
                    <a href="{{ route('products.show', $product) }}"
                        class="flex flex-col bg-gray-200 shadow-md rounded-lg overflow-hidden">
                        <img src="{{ $product->image }}" class="h-80 w-full object-cover" alt="{{ $product->name }}">
                        <div class="p-3 text-center">
                            <p class="text-sm font-medium text-gray-700 uppercase">{{ $product->name }}</p>
                            <p class="text-lg font-semibold text-indigo-600">
                                ${{ number_format($product->sale_price, 2) }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Botones de navegación -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <!-- Botón para ver todos los productos de la categoría -->
    <div class="text-center mt-6">
        <a href="{{ route('categories.show', $category) }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Ver todo en {{ $category->name }}
        </a>
    </div>

    {{-- JS para usar swiper --}}
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                new Swiper(".mySwiper", {
                    slidesPerView: 4, // Mostrar 4 productos a la vez
                    spaceBetween: 20, // Espacio entre productos
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    loop: true, // Hacer que el carrusel sea infinito
                });
            });
        </script>
    @endpush
</div>
