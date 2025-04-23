<div class="dark">
    <header class="bg-gray-700 backdrop-blur-sm bg-opacity-90 border-b border-gray-800 shadow-xl sticky top-0 z-50">
        <x-container class="px-4 py-4">
            <!-- Primera fila - Logo, buscador y acciones -->
            <div class="grid grid-cols-3 w-full items-center">
                <!-- Buscador (Desktop) -->
                <div class="hidden md:flex items-center justify-start">
                    <x-dropdown width="96" align="left">
                        <x-slot name="trigger">
                            <div class="flex items-center space-x-2 w-full max-w-md">
                                <button class="text-xl text-indigo-400 hover:text-indigo-300 transition-colors">
                                    <i class="fas fa-search"></i>
                                </button>
                                <x-input
                                    class="w-full bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Buscar productos..." name="search" oninput="search(this.value)"
                                    wire:model="search" />
                            </div>
                        </x-slot>

                        <x-slot name="content" class="bg-gray-800 border border-gray-700">
                            @forelse($this->filteredProducts as $product)
                                <x-dropdown-link href="{{ route('products.show', $product) }}"
                                    class="hover:bg-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-10 h-10 rounded-md object-cover" src="{{ $product->image }}"
                                            alt="">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-white truncate">
                                                {{ $product->name }}
                                            </p>
                                            <p class="text-sm text-indigo-400 font-bold">
                                                ${{ number_format($product->sale_price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </x-dropdown-link>
                                @if (!$loop->last)
                                    <div class="border-t border-gray-700"></div>
                                @endif
                            @empty
                                <x-dropdown-link class="text-gray-400">
                                    No se encontraron productos
                                </x-dropdown-link>
                            @endforelse

                            <div class="border-t border-gray-700"></div>
                            <x-dropdown-link>
                                <button wire:click="searchProduct"
                                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="@json(empty($search))">
                                    Ver todos los resultados
                                </button>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Logo XL -->
                <div class="flex items-center justify-center col-span-2 md:col-span-1">
                    <a href="/"
                        class="group inline-flex flex-col items-center transform hover:scale-105 transition-transform duration-300">
                        <span
                            class="text-4xl md:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent tracking-tight">
                            TIENDA
                        </span>
                        <span
                            class="text-sm md:text-base lg:text-lg text-gray-400 group-hover:text-indigo-300 transition-colors mt-1">
                            ONLINE
                        </span>
                    </a>
                </div>

                <!-- Acciones de usuario -->
                <div class="flex items-center justify-end space-x-6">
                    <!-- Menú de usuario -->
                    <x-dropdown>
                        <x-slot name="trigger">
                            @auth
                                <button
                                    class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-indigo-500 hover:border-indigo-400 transition-colors"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <button class="text-gray-400 hover:text-indigo-400 transition-colors">
                                    <i class="fas fa-user-circle text-xl"></i>
                                </button>
                            @endauth
                        </x-slot>

                        <x-slot name="content" class="bg-gray-800 border border-gray-700">
                            @guest
                                <div class="px-4 py-3">
                                    <div class="flex justify-center mb-2">
                                        <a href="{{ route('login') }}" class="w-full btn btn-indigo">
                                            <i class="fas fa-sign-in-alt mr-2"></i> Iniciar sesión
                                        </a>
                                    </div>
                                    <p class="text-xs text-gray-400 text-center">
                                        ¿Nuevo aquí?
                                        <a href="{{ route('register') }}" class="text-indigo-400 hover:underline">
                                            Regístrate
                                        </a>
                                    </p>
                                </div>
                            @else
                                <x-dropdown-link href="{{ route('profile.show') }}" class="hover:bg-gray-700">
                                    <i class="fas fa-user-circle mr-2 text-indigo-400"></i> Mi perfil
                                </x-dropdown-link>

                                @if (auth()->check())
                                    <x-dropdown-link href="{{ route('orders.index') }}" class="hover:bg-gray-700">
                                        <i class="fas fa-clipboard-list mr-2 text-indigo-400"></i> Mis pedidos
                                    </x-dropdown-link>
                                @endif

                                @if (Auth::user()->hasRole('admin'))
                                    <x-dropdown-link href="{{ route('admin.dashboard') }}"
                                        class="bg-indigo-900 hover:bg-indigo-800">
                                        <i class="fas fa-lock mr-2"></i> Administrador
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-700"></div>

                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                                        class="text-red-400 hover:bg-gray-700 hover:text-red-300">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                                    </x-dropdown-link>
                                </form>
                            @endguest
                        </x-slot>
                    </x-dropdown>

                    <!-- Carrito -->
                    <a href="{{ route('cart.index') }}" class="relative group">
                        <i
                            class="fas fa-shopping-cart text-xl text-gray-400 group-hover:text-indigo-400 transition-colors"></i>
                        <span id="cart-count"
                            class="absolute -top-2 -right-2 inline-flex items-center justify-center w-5 h-5 bg-indigo-600 rounded-full text-xs font-bold text-white group-hover:bg-indigo-500 transition-colors">
                            {{ Cart::instance('shopping')->count() }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- Buscador (Mobile) -->
            <div class="mt-4 md:hidden">
                <x-dropdown width="full" align="center">
                    <x-slot name="trigger">
                        <div class="flex items-center space-x-2">
                            <x-input
                                class="w-full bg-gray-800 border-gray-700 text-white placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Buscar..." name="search" oninput="search(this.value)"
                                wire:model="search" />
                        </div>
                    </x-slot>

                    <x-slot name="content" class="bg-gray-800 border border-gray-700">
                        @forelse($this->filteredProducts as $product)
                            <x-dropdown-link href="{{ route('products.show', $product) }}" class="hover:bg-gray-700">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-md object-cover" src="{{ $product->image }}"
                                        alt="">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white truncate">
                                            {{ $product->name }}
                                        </p>
                                        <p class="text-sm text-indigo-400 font-bold">
                                            ${{ number_format($product->sale_price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            @if (!$loop->last)
                                <div class="border-t border-gray-700"></div>
                            @endif
                        @empty
                            <x-dropdown-link class="text-gray-400">
                                No se encontraron productos
                            </x-dropdown-link>
                        @endforelse

                        <div class="border-t border-gray-700"></div>
                        <x-dropdown-link>
                            <button wire:click="searchProduct"
                                class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="@json(empty($search))">
                                Ver todos los resultados
                            </button>
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Categorías -->
            <div class="w-full pt-4 flex flex-wrap justify-center items-center gap-2 md:gap-4">
                @foreach ($categories as $category)
                    <div class="relative group" x-data="{ open: false }" @click.away="open = false"
                        @close.stop="open = false">
                        <button @click="open = !open"
                            class="px-4 py-2 text-sm md:text-base font-medium rounded-full bg-gray-300 text-gray-900 hover:bg-gray-500 hover:text-white transition-colors flex items-center">
                            {{ $category->name }}
                            <svg :class="open ? 'rotate-180' : 'rotate-0'"
                                class="ml-1 w-4 h-4 text-indigo-400 transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-50 mt-1 w-48 sm:w-56 md:w-64 rounded-lg shadow-lg bg-gray-800 border border-gray-700 left-1/2 transform -translate-x-1/2"
                            style="display: none;">
                            <div class="py-1">
                                @foreach ($category->subcategories as $subcategory)
                                    <a href="{{ route('subcategories.show', $subcategory) }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                        {{ $subcategory->name }}
                                    </a>
                                @endforeach

                                <div class="border-t border-gray-700"></div>
                                <a href="{{ route('categories.show', $category) }}"
                                    class="block px-4 py-2 text-sm font-medium text-indigo-400 hover:bg-gray-700 hover:text-indigo-300 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> Ver todo
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Ofertas -->
                <a href="{{ route('offers.show') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-full bg-gradient-to-r from-red-600 to-pink-600 text-white hover:from-red-500 hover:to-pink-500 transition-colors shadow-lg hover:shadow-red-500/20">
                    <i class="fas fa-tag mr-2"></i> ¡Ofertas!
                </a>
            </div>
        </x-container>
    </header>

    @push('js')
        <script>
            Livewire.on('cartUpdated', (count) => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.innerText = count;
                    // Efecto de animación
                    cartCount.classList.add('animate-ping');
                    setTimeout(() => {
                        cartCount.classList.remove('animate-ping');
                    }, 500);
                }
            });

            function search(value) {
                Livewire.dispatch('search', {
                    search: value
                });
            }

            // Cambiar tema oscuro/claro
            document.addEventListener('DOMContentLoaded', () => {
                if (localStorage.getItem('dark-mode') === 'false') {
                    document.documentElement.classList.remove('dark');
                }
            });
        </script>
    @endpush
</div>
