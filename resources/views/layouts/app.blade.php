<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EcommerceSTP</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- CSS, lo uso para el carrusel --}}
    @stack('css')

    {{-- FontAwesome --}}
    <script src="https://kit.fontawesome.com/7b2b373cff.js" crossorigin="anonymous"></script>

    {{-- Mercado pago --}}
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    {{-- <x-banner /> --}}

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        {{-- @livewire('navigation-menu') --}}

        @livewire('navigation')

        <!-- Contenido de la pagina -->
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <div class="mt-16">
            @include('layouts.partials.app.footer')
        </div>
    </div>

    @stack('modals')

    {{-- Alertas --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts

    @stack('js')

    {{-- Verifica si hay un mensaje de alerta almacenado en la sesión y lo muestra usando SweetAlert --}}
    @if (session('swal'))
        <script>
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif

    <script>
        Livewire.on('swal', data => {
            Swal.fire(data[0]);
        })
    </script>

</body>

</html>
