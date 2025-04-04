<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">

    <div class="px-3 py-3 lg:px-5 lg:pl-3">

        <div class="flex items-center justify-between">
            {{-- Boton de apertura del *sidebar* y logo: --}}
            <div class="flex items-center justify-start rtl:justify-end">
                {{-- Botón para mostrar/ocultar el *sidebar*: --}}
                <button x-on:click="sidebarOpen = !sidebarOpen" data-drawer-target="logo-sidebar"
                    data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">

                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">

                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>

                {{-- Logo de la Compañía --}}
                <a href="/admin" class="flex ms-2 md:me-24">

                    <img src="{{ asset('img/logopercha.png') }}" class="h-8 me-3 rounded-lg bg-white"
                        alt="FlowBite Logo" />

                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                        Panel Administrador
                    </span>
                </a>
            </div>

            {{-- Menú Desplegable del Usuario --}}
            <div class="flex items-center ms-3 ">
                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        {{-- Foto de Perfil --}}
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="h-8 w-8 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                        @else
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}

                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        @endif
                    </x-slot>

                    {{-- Contenido del Menú Desplegable --}}
                    <x-slot name="content">
                        {{-- perfil del usuario --}}
                        <x-dropdown-link href="{{ route('profile.show') }}">
                            Perfil
                        </x-dropdown-link>

                        {{-- boton para ir a la página principal de ventas --}}
                        <x-dropdown-link href="{{ route('welcome.index') }}"
                            class="bg-indigo-600">
                            Página Principal
                        </x-dropdown-link>

                        <hr class="mx-2 border-gray-200 dark:border-gray-600">

                        {{-- cerrar sesión --}}
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-dropdown-link  href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>


{{-- Este código define una barra de navegación fija en la parte superior de la página. La estructura incluye elementos interactivos como un botón para mostrar/ocultar un *sidebar*, un logo, y un menú desplegable de usuario. Aquí se detallan las partes principales del código:

### Estructura General del `<nav>`
- **`<nav>`**: Define un contenedor de navegación que generalmente se usa para encabezados o menús principales en una página web.

#### Atributos del `<nav>`:
- **`class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"`**:
    - `fixed top-0`: Hace que la barra de navegación esté fija en la parte superior de la pantalla.
    - `z-50`: Define el índice de superposición (z-index) para asegurarse de que esté por encima de otros elementos.
    - `w-full`: Ancho completo para que la barra ocupe todo el ancho de la ventana.
    - `bg-white` y `dark:bg-gray-800`: Define un fondo blanco en el modo claro y gris oscuro en modo oscuro.
    - `border-b border-gray-200 dark:border-gray-700`: Añade un borde inferior en modo claro y un borde más oscuro en modo oscuro.

### Contenido del `<nav>`:
- **`<div class="px-3 py-3 lg:px-5 lg:pl-3">`**:
    - Define un contenedor con relleno (padding) para que el contenido tenga espacio alrededor.
    - `px-3 py-3`: Relleno en todos los lados, horizontal de 0.75rem y vertical de 0.75rem.
    - `lg:px-5 lg:pl-3`: Aumenta el relleno horizontal a 1.25rem y el relleno izquierdo a 0.75rem en pantallas grandes.

#### Sección de la Izquierda:
- **`<div class="flex items-center justify-between">`**:
    - Utiliza Flexbox para alinear los elementos horizontalmente.
    - `items-center`: Alinea los elementos en el centro de la línea.
    - `justify-between`: Distribuye los elementos para que estén separados con el mayor espacio posible entre ellos.

##### Botón para Mostrar/Ocultar el Sidebar:
- **`<button x-on:click="sidebarOpen = !sidebarOpen"`**:
    - **`x-on:click="sidebarOpen = !sidebarOpen"`**: Esto es una directiva de Alpine.js (o Vue.js), que alterna el valor de `sidebarOpen` al hacer clic, lo que a su vez muestra u oculta el *sidebar*.
    - **`data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"`**: Utilizado para identificar y controlar el *sidebar* mediante atributos personalizados.
    - **`aria-controls="logo-sidebar"`**: Describe la relación del botón con el *sidebar* para lectores de pantalla.
    - **`class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden ..."`**:
        - `inline-flex items-center`: Muestra el botón como un elemento en línea que utiliza Flexbox.
        - `sm:hidden`: Oculta el botón en pantallas medianas y más grandes, por lo que solo aparece en dispositivos móviles o pequeños.
        - `hover:bg-gray-100 focus:outline-none focus:ring-2 ...`: Define el estilo cuando se pasa el ratón sobre el botón o cuando está enfocado, proporcionando una mejor accesibilidad.
    - **`<span class="sr-only">Open sidebar</span>`**: Proporciona un texto accesible para describir el botón para lectores de pantalla.
    - **`<svg>`**: Un ícono SVG que representa las "tres líneas" de un menú (*hamburger menu*), indicando que se puede abrir el *sidebar*.

##### Logo de la Compañía:
- **`<a href="https://flowbite.com" class="flex ms-2 md:me-24">`**:
    - `href="https://flowbite.com"`: Enlace que lleva a la página de Flowbite.
    - `class="flex ms-2 md:me-24"`: Usa Flexbox para alinear el logo y el texto, con un margen en dispositivos medianos.
    - **`<img>`**: Muestra el logo de la compañía con una altura de 2rem (`h-8`).
    - **`<span>`**: Muestra el nombre "Flowbite" al lado del logo.

#### Sección de la Derecha:
- **`<div class="flex items-center">`**:
    - Contiene el menú desplegable del usuario.

##### Menú Desplegable del Usuario:
- **`<x-dropdown>`**: Es un componente de Blade para crear un menú desplegable.
    - **`<x-slot name="trigger">`**: Define el contenido que activa el menú desplegable, que en este caso es la foto de perfil del usuario o su nombre si no tiene una foto.

###### Foto de Perfil:
- **`@if (Laravel\Jetstream\Jetstream::managesProfilePhotos())`**:
    - Verifica si la aplicación usa fotos de perfil gestionadas por Jetstream.
    - **`<button>`**: Un botón que muestra la foto de perfil del usuario.
    - **`<img>`**: Muestra la imagen de perfil redondeada (`rounded-full`).
- **`@else`**: Si no hay foto de perfil, se muestra un botón con el nombre del usuario y un ícono para expandir el menú.

###### Contenido del Menú Desplegable:
- **`<x-slot name="content">`**: Define el contenido del menú desplegable.
    - **`<div class="block px-4 py-2 text-xs text-gray-400">`**: Muestra un encabezado para gestionar la cuenta.
    - **`<x-dropdown-link>`**: Genera enlaces para acceder al perfil y gestionar los tokens de la API si están habilitados.
    - **`<div class="border-t border-gray-200 dark:border-gray-600"></div>`**: Añade una línea de separación entre los enlaces del perfil y el botón de cierre de sesión.
    - **Formulario de cierre de sesión**:
        - **`<form method="POST" action="{{ route('logout') }}" x-data>`**: Utiliza Alpine.js para manejar la acción de enviar el formulario cuando se hace clic en el enlace de cerrar sesión.
        - **`@csrf`**: Token de protección contra ataques CSRF.
        - **`<x-dropdown-link>`**: Enlace para cerrar sesión con un evento `@click.prevent` para prevenir el comportamiento por defecto del enlace y ejecutar la función de cierre de sesión.

### Resumen del Funcionamiento:
- La barra de navegación contiene un botón para mostrar/ocultar un *sidebar*, que aparece en dispositivos móviles.
- Incluye un logo con un enlace a la página principal.
- Muestra un menú desplegable que permite acceder a opciones de la cuenta del usuario y cerrar sesión.
- Utiliza Tailwind CSS para el estilo y Jetstream para la gestión de cuentas, con directivas de Alpine.js para el control interactivo.

Este código está orientado a una aplicación que utiliza Laravel Jetstream, con un enfoque en la experiencia de usuario tanto en dispositivos móviles como en temas claros y oscuros. --}}
