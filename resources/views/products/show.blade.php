<x-app-layout>

    {{-- Breadcrumbs --}}
    <x-container class="px-4 py-4">

        <nav class="flex" aria-label="Breadcrumb">
            {{-- Enlaces --}}
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">

                {{-- Home --}}
                <li class="inline-flex items-center">
                    <a href="/"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Home
                    </a>
                </li>

                {{-- Categoría --}}
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('categories.show', $product->subcategory->category) }}"
                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                            {{ $product->subcategory->category->name }}
                        </a>
                    </div>
                </li>

                {{-- Subcategoría --}}
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('subcategories.show', $product->subcategory) }}"
                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                            {{ $product->subcategory->name }}
                        </a>
                    </div>
                </li>

                {{-- Producto --}}
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ $product->name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

    </x-container>

    <x-container>
        <div class="card">
            <div>

                {{-- Imagen --}}
                {{-- <div class="col-span-1">
                    <figure
                        class="rounded-lg overflow-hidden shadow-lg border border-gray-700 bg-gradient-to-b from-gray-800 to-gray-900">
                        <img src="{{ $product->image }}" class="aspect-[1/1] object-contain object-center w-full"
                            alt="Imagen del producto">
                    </figure>
                </div> --}}


                {{-- Llamo al componente add-to-cart-variants --}}
                <div>
                    @livewire('products.add-to-cart', ['product' => $product])
                </div>
            </div>
        </div>

        {{-- Carrusel de productos de la misma categoria --}}
        <div class="">
            @livewire('products.product-carousel', ['category' => $product->subcategory->category, 'product' => $product])
        </div>
    </x-container>


</x-app-layout>
