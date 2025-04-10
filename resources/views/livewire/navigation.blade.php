{{-- Para darle funcionalidad al boton de apertura del menu utilizo alpine con x-data --}}
<div>
    {{-- Encabezado --}}
    <header class="bg-[#fefefeeb] ">

        <x-container class="px-4 py-4">
            <div class="grid grid-cols-3 w-full">
                {{-- Buscador --}}
                <div class="flex items-center justify-start">
                    {{-- Boton de busqueda --}}
                    <div class="flex-1 hidden md:block">
                        <x-dropdown width="96" align="center">
                            {{-- En este slot se define el boton --}}
                            <x-slot name="trigger">
                                <div class="flex space-x-2">
                                    {{-- Boton de busqueda --}}
                                    <button class="text-xl md:text-2xl">
                                        <i class="fas fa-search text-black hover:text-gray-700"></i>
                                    </button>

                                    {{-- Buscador --}}
                                    <x-input class="w-full" placeholder="Buscar..." name="search"
                                        oninput="search(this.value)" wire:model="search">
                                    </x-input>
                                </div>
                            </x-slot>

                            {{-- En este slot se define el contenido --}}
                            <x-slot name="content">

                                {{-- Lista de productos --}}
                                @foreach ($products as $product)
                                    <x-dropdown-link href="{{ route('products.show', $product) }}">
                                        <div class="flex uppercase">
                                            {{-- Imagen del producto --}}
                                            <img class="w-12 h-12 object-cover object-center mr-4"
                                                src="{{ $product->image }}" alt="">

                                            <div class="">
                                                {{-- Nombre del producto --}}
                                                <p class="text-sm font-bold truncate">
                                                    {{ $product->name }}
                                                </p>

                                                {{-- Precio del producto --}}
                                                <p class="text-sm font-bold text-gray-400">
                                                    ${{ $product->sale_price }}
                                                </p>
                                            </div>
                                        </div>
                                    </x-dropdown-link>
                                    {{-- Separador --}}
                                    <div class="border-t border-gray-500"></div>
                                @endforeach

                                {{-- Boton para ver mas resultados --}}
                                <x-dropdown-link>
                                    <button wire:click="searchProduct"
                                        class="w-full disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="@json(empty($search))">
                                        Ver más resultados
                                    </button>
                                </x-dropdown-link>
                            </x-slot>

                        </x-dropdown>
                    </div>
                </div>

                {{-- Logo --}}
                <div class="flex items-center space-x-4 justify-center ">
                    <a href="/" class="inline-flex flex-col items-center">
                        <img src="{{ asset('img/logopercha.png') }}" alt=""
                            class="h-12 w-20 object-cover object-center mb-1">

                        <span class="text-2xl md:text-3xl leading-6 font-bold">
                            Tienda
                        </span>

                        <span class="text-xs text-[#423D41]">
                            Online
                        </span>
                    </a>

                </div>

                {{-- Boton de usuario y carrito --}}
                <div class="flex items-center justify-end  space-x-8 mr-3">

                    {{-- Boton de usuario --}}
                    <x-dropdown>
                        {{-- En este slot se define el boton --}}
                        <x-slot name="trigger">

                            {{-- La directiva auth se ejecuta cuando el usuario esta logueado --}}
                            @auth
                                {{-- Imagen de perfil del usuario --}}
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-700 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                {{-- Imagen de perfil del usuario sin logueo --}}
                                <button class="text-l md:text-xl">
                                    <i class="fas fa-user text-black hover:text-gray-700"></i>
                                </button>
                            @endauth

                        </x-slot>

                        {{-- En este slot se define el contenido --}}
                        <x-slot name="content">
                            {{-- La directiva guest se ejecuta cuando el usuario no esta logueado --}}
                            @guest
                                {{-- Inicio de sesión --}}
                                <div class="px-4 py-2">
                                    {{-- Boton para iniciar sesion --}}
                                    <div class="flex justify-center">
                                        <a href="{{ route('login') }}" class="btn btn-indigo">
                                            Iniciar sesión
                                        </a>
                                    </div>

                                    {{-- Boton de registro --}}
                                    <p class="text-sm text-white text-center mt-2">
                                        ¿No tienes cuenta?
                                        <a href="{{ route('register') }}" class="hover:underline">
                                            Registrate
                                        </a>
                                    </p>
                                </div>
                            @else
                                {{-- Boton para ir a mi perfil --}}
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    Mi perfil
                                </x-dropdown-link>

                                @if (auth()->check())
                                    {{-- Boton para ver mis pedidos --}}
                                    <x-dropdown-link href="{{ route('orders.index') }}">
                                        Mis pedidos
                                    </x-dropdown-link>
                                @endif

                                @if (Auth::user()->hasRole('admin'))
                                    {{-- Boton para ir a la pagina de administrador si el usuario es administrador --}}
                                    <x-dropdown-link href="{{ route('admin.dashboard') }}" class="bg-indigo-800">
                                        Administrador
                                    </x-dropdown-link>
                                @endif

                                {{-- linea del separador --}}
                                <div class="border-t border-gray-400"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        Cerrar sesión
                                    </x-dropdown-link>
                                </form>


                            @endguest
                        </x-slot>

                    </x-dropdown>


                    {{-- Carrito --}}
                    <a href="{{ route('cart.index') }}" class="relative ">
                        <i class="fas fa-shopping-cart text-black hover:text-gray-700 text-l md:text-xl"></i>
                        {{-- Cantidad de items --}}
                        <span id="cart-count"
                            class="absolute -top-2 -end-4 inline-flex w-5 h-5 items-center justify-center bg-red-500 rounded-full text-xs font-bold text-white">

                            {{ Cart::instance('shopping')->count() }}
                        </span>
                    </a>
                </div>
            </div>

            {{-- Buscador para pantalla pequeña --}}
            <div class="mt-4 md:hidden">
                <x-dropdown width="80" align="center">
                    {{-- En este slot se define el boton --}}
                    <x-slot name="trigger">
                        <div class="flex space-x-2">
                            {{-- Boton de busqueda --}}
                            {{-- <button class="text-xl md:text-2xl">
                                <i class="fas fa-search text-black hover:text-gray-700"></i>
                            </button> --}}

                            {{-- Buscador --}}
                            <x-input class="w-full" placeholder="Buscar..." name="search" oninput="search(this.value)"
                                {{-- Llamo al metodo search() javascript cada vez que cambia el input --}} wire:model="search">
                            </x-input>
                        </div>
                    </x-slot>

                    {{-- En este slot se define el contenido --}}
                    <x-slot name="content">

                        {{-- Lista de productos --}}
                        @foreach ($products as $product)
                            <x-dropdown-link href="{{ route('products.show', $product) }}">
                                <div class="flex uppercase">
                                    {{-- Imagen del producto --}}
                                    <img class="w-12 h-12 object-cover object-center mr-4" src="{{ $product->image }}"
                                        alt="">

                                    <div class="">
                                        {{-- Nombre del producto --}}
                                        <p class="text-sm font-bold truncate">
                                            {{ $product->name }}
                                        </p>

                                        {{-- Precio del producto --}}
                                        <p class="text-sm font-bold text-gray-400">
                                            ${{ $product->sale_price }}
                                        </p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                        @endforeach

                        {{-- Boton para ver mas resultados --}}
                        <x-dropdown-link>
                            <button wire:click="searchProduct"
                                class="w-full disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="@json(empty($search))">
                                Ver más resultados
                            </button>
                        </x-dropdown-link>
                    </x-slot>

                </x-dropdown>
            </div>

            {{-- Categorias --}}
            <div class="w-full pt-4 flex flex-wrap justify-center items-center gap-3 md:gap-5">
                @foreach ($categories as $category)
                    <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                        <button @click="open = !open"
                            class="text-lg sm:text-xl md:text-2xl w-32 sm:w-40 md:w-48 flex items-center justify-center">
                            {{ $category->name }}

                            <!-- Ícono de flecha -->
                            <svg x-bind:class="open ? 'rotate-180' : 'rotate-0'"
                                class="ml-2 w-5 h-5 transition-transform duration-300" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 sm:w-56 md:w-64 rounded-md shadow-lg bg-white left-1/2 transform -translate-x-1/2"
                            style="display: none;">

                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1">
                                @foreach ($category->subcategories as $subcategory)
                                    <a href="{{ route('subcategories.show', $subcategory) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        {{ $subcategory->name }}
                                    </a>
                                @endforeach

                                {{-- Ver todo en esta categoria --}}
                                <a href="{{ route('categories.show', $category) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Ver todo
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Botón de Ofertas --}}
                <a href="{{ route('offers.show') }}"
                    class="flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-full shadow-md transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                        </path>
                    </svg>
                    ¡Ofertas Especiales!
                </a>
            </div>

            <!-- Agrega Font Awesome para los iconos -->
            <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>

        </x-container>

    </header>



    @push('js')
        <script>
            // Mantengo a livewire en escucha del evento cartUpdated para actualizar la cantidad de items del carrito.
            Livewire.on('cartUpdated', (count) => {
                document.getElementById('cart-count').innerText = count;
            });

            function search(value) {
                // Emito un evento para ser escuchado por el componente Filter
                Livewire.dispatch('search', {
                    search: value
                });
            }
        </script>
    @endpush
</div>
