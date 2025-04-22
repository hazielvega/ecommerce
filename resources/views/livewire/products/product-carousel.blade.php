<div class="mt-16">
    {{-- CSS para usar swiper --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            .product-card {
                transition: all 0.3s ease;
                border-radius: 0.5rem;
                overflow: hidden;
                background: #1f2937; /* Fondo oscuro por defecto */
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25), 0 2px 4px -1px rgba(0, 0, 0, 0.15);
                border: 1px solid #374151; /* Borde sutil */
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
                border-color: #4b5563; /* Borde más visible al hover */
            }

            .swiper-slide {
                height: auto;
                padding: 0.5rem;
            }

            .swiper-button-next,
            .swiper-button-prev {
                color: #a5b4fc; /* Color indigo claro para modo oscuro */
                background: rgba(31, 41, 55, 0.8); /* Fondo oscuro semitransparente */
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                border: 1px solid #4b5563;
            }

            .swiper-button-next:hover,
            .swiper-button-prev:hover {
                background: rgba(55, 65, 81, 0.8);
            }

            .swiper-button-next::after,
            .swiper-button-prev::after {
                font-size: 1.2rem;
            }

            .product-image-container {
                position: relative;
                overflow: hidden;
                background: #111827; /* Fondo más oscuro para el área de la imagen */
            }

            .product-image {
                transition: transform 0.3s ease;
            }

            .product-card:hover .product-image {
                transform: scale(1.03);
                opacity: 0.9;
            }

            .discount-badge {
                background: #dc2626; /* Rojo más oscuro */
                color: white;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            }

            .product-title {
                color: #e5e7eb; /* Texto claro */
            }

            .original-price {
                color: #9ca3af; /* Texto gris para precio tachado */
            }

            .sale-price {
                color: #a5b4fc; /* Color indigo claro */
            }

            .view-all-button {
                background: #6366f1; /* Indigo 500 */
                color: white;
            }

            .view-all-button:hover {
                background: #4f46e5; /* Indigo 600 */
            }

            .no-products-text {
                color: #9ca3af; /* Texto gris para mensaje sin productos */
            }
        </style>
    @endpush

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6 text-indigo-200">
            Productos relacionados
        </h2>

        @if ($products->count() > 0)
            <!-- Swiper Container -->
            <div class="swiper productSwiper relative">
                <div class="swiper-wrapper pb-8">
                    @foreach ($products as $product)
                        <div class="swiper-slide">
                            <a href="{{ route('products.show', $product) }}" class="product-card block h-full">
                                <div class="product-image-container">
                                    <img src="{{ $product->image }}" class="product-image h-72 w-full object-cover"
                                        alt="{{ $product->name }}" loading="lazy">
                                    @if ($product->discount > 0)
                                        <span class="discount-badge absolute top-2 right-2 text-xs font-bold px-2 py-1 rounded-full">
                                            -{{ $product->discount }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="product-title text-sm font-medium line-clamp-2 mb-2 uppercase">
                                        {{ $product->name }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        @if ($product->discount > 0)
                                            <div>
                                                <span class="original-price text-sm line-through mr-2">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                                <span class="sale-price text-lg font-semibold">
                                                    ${{ number_format($product->sale_price, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="sale-price text-lg font-semibold">
                                                ${{ number_format($product->sale_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Botones de navegación -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <!-- Botón para ver todos los productos de la categoría -->
            <div class="text-center mt-8">
                <a href="{{ route('subcategories.show', $subcategory) }}"
                    class="view-all-button inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm transition-colors duration-200">
                    Ver todo en {{ $subcategory->name }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        @else
            <div class="text-center py-8">
                <p class="no-products-text">No hay productos relacionados disponibles</p>
            </div>
        @endif
    </div>

    {{-- JS para usar swiper --}}
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const swiper = new Swiper(".productSwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    loop: {{ $products->count() > 4 ? 'true' : 'false' }},
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                });
            });
        </script>
    @endpush
</div>