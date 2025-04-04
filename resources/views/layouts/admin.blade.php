{{-- Esto es para hacer la miga de pan dinamica --}}
@props(['breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Administrador</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- FontAwesome --}}
    <script src="https://kit.fontawesome.com/7b2b373cff.js" crossorigin="anonymous"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-700" x-data="{
    sidebarOpen: false
}" :class="{
    'overflow-y-hidden': sidebarOpen
}">
    <!-- Define el cuerpo de la página con clases de fuente, antialiasing y fondo oscuro.
         Usa Alpine.js (x-data) para manejar el estado de "sidebarOpen" (barra lateral abierta).
         Cambia el estilo de desbordamiento cuando la barra lateral está abierta. -->

    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 sm:hidden" style="display: none" x-show="sidebarOpen"
        x-on:click="sidebarOpen = false">
    </div>
    <!-- Div que muestra un fondo oscuro semitransparente cuando la barra lateral está abierta en pantallas pequeñas.
         Está configurado para cerrar la barra lateral al hacer clic. -->

    @include('layouts.partials.admin.navigation')
    <!-- Incluye la navegación desde un archivo parcial en la carpeta de layouts/admin. -->

    @include('layouts.partials.admin.sidebar')
    <!-- Incluye la barra lateral desde un archivo parcial en la carpeta de layouts/admin. -->

    <div class="p-4 sm:ml-64">
        <div class="p-4 border border-white bg-[#0f344d] rounded-lg mt-14">
            <!-- Contenedor principal con padding y borde blanco, fondo personalizado y bordes redondeados. -->

            <div class="flex justify-between items-center">
                @include('layouts.partials.admin.breadcrumb')
                <!-- Incluye el breadcrumb (rastro de navegación) desde un archivo parcial. -->

                @isset($action)
                    <div class="mb-4">
                        {{ $action }}
                    </div>
                @endisset
                <!-- Muestra la variable $action si está definida, colocándola en un div con margen inferior. -->
            </div>

            {{ $slot }}
            <!-- Muestra el contenido dinámico inyectado en el componente a través de la variable $slot. -->
        </div>
    </div>

    {{-- Alertas --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluye la biblioteca SweetAlert2 para mostrar alertas en el frontend. -->

    @livewireScripts
    <!-- Incluye los scripts de Livewire para el soporte de interactividad en componentes de Laravel Livewire. -->

    @stack('js')
    <!-- Permite empujar scripts adicionales desde otras vistas hacia esta sección utilizando la pila 'js'. -->

    @if (session('swal'))
        <script>
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif
    <!-- Si existe un mensaje de alerta en la sesión ('swal'), muestra la alerta con SweetAlert2 usando sus datos. -->

    <script>
        // Escucha un evento emitido por Livewire llamado 'swal'.
        // Cuando el evento 'swal' es disparado, se espera que contenga datos (en este caso, un array).
        Livewire.on('swal', data => {
            // Se utiliza el primer elemento del array (data[0]) como argumento para mostrar una alerta
            // con SweetAlert.
            Swal.fire(data[0]);
        })
    </script>
</body>


</html>
