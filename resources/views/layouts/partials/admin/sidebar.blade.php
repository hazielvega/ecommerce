@php
    $links = [
        [
            // Dashboard
            'icon' => 'fa-solid fa-gauge',
            'name' => 'Dashboard',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            // Header
            'header' => 'Administración de la página',
        ],
        [
            // Usuarios
            'name' => 'Usuarios',
            'icon' => 'fa-solid fa-users',
            'route' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
        [
            // Opciones
            'name' => 'Opciones',
            'icon' => 'fa-solid fa-gear',
            'route' => route('admin.options.index'),
            'active' => request()->routeIs('admin.options.*'),
        ],
        [
            // Subcategorias
            'name' => 'Subcategorías',
            'icon' => 'fa-solid fa-tag',
            'route' => route('admin.subcategories.index'),
            'active' => request()->routeIs('admin.subcategories.*'),
        ],
        [
            // Categorias
            'name' => 'Categorías',
            'icon' => 'fa-solid fa-tags',
            'route' => route('admin.categories.index'),
            'active' => request()->routeIs('admin.categories.*'),
        ],
        [
            // Productos
            'name' => 'Productos',
            'icon' => 'fa-solid fa-box',
            'route' => route('admin.products.index'),
            'active' => request()->routeIs('admin.products.*'),
        ],
        [
            // Portadas
            'name' => 'Portadas',
            'icon' => 'fa-solid fa-images',
            'route' => route('admin.covers.index'),
            'active' => request()->routeIs('admin.covers.*'),
        ],
        [
            // Header
            'header' => 'Ordenes y entregas',
        ],
        [
            // Ordenes
            'name' => 'Ordenes',
            'icon' => 'fa-solid fa-truck',
            'route' => route('admin.orders.index'),
            'active' => request()->routeIs('admin.orders.*'),
        ],
        [
            // Reportes
            'name' => 'Ventas',
            'icon' => 'fa-solid fa-chart-line',
            'route' => route('admin.reports.index'),
            'active' => request()->routeIs('admin.reports.*'),
        ],
    ];
@endphp



<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    :class="{
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }"
    aria-label="Sidebar">
    <!-- Barra lateral fija en la parte izquierda de la pantalla.
         Usa Alpine.js para manejar su visibilidad con la clase dinámica 'translate-x-0' si sidebarOpen es true.
         Se adapta al modo oscuro con clases 'dark'. -->

    {{-- Lista de botones del *sidebar*: --}}
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <!-- Contenedor de la lista con padding y overflow vertical para desplazarse.
             Ajusta el color de fondo según el tema claro u oscuro. -->

        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                <li>
                    @isset($link['header'])
                        <div class="px-3 py-2 font-semibold text-xs text-gray-500 uppercase">
                            {{ $link['header'] }}
                        </div>
                        <!-- Si el elemento de la lista contiene un encabezado (header), lo muestra como un texto en gris y en mayúsculas. -->
                    @else
                        <a href="{{ $link['route'] }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-blue-400' : '0' }}">
                            {{-- Icono del boton --}}
                            <span class="inline-flex w-6 h-6 justify-center items-center">
                                <i class="{{ $link['icon'] }}"></i>
                            </span>
                            <!-- Muestra el ícono del botón con una clase dinámica según el ícono definido en cada enlace. -->

                            {{-- Nombre del boton --}}
                            <span class="ms-3">
                                {{ $link['name'] }}
                            </span>
                            <!-- Muestra el nombre del botón, desplazado ligeramente a la derecha con 'ms-3'. -->
                        </a>
                    @endisset
                </li>
            @endforeach
            <!-- Recorre la variable $links para mostrar cada enlace. Si el enlace tiene 'header', muestra el encabezado;
                 de lo contrario, muestra un enlace con ícono, nombre, y un color de fondo si el enlace está activo. -->
        </ul>
    </div>
</aside>
