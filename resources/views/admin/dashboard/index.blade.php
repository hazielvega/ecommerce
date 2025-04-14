<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
]">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Tarjeta de Bienvenida -->
        <div
            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-6 flex items-start">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full object-cover ring-2 ring-white"
                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Bienvenido, {{ auth()->user()->name }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Último acceso: {{ auth()->user()->last_login_at?->format('d/m/Y H:i') ?? 'Primer ingreso' }}
                    </p>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Información de la Empresa -->
        <div
            class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl shadow-md overflow-hidden border border-gray-100 col-span-2">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ config('app.name') }}
                    </h2>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                        v{{ config('app.version') }}
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Entorno</p>
                        <p
                            class="font-medium @if (app()->environment('production')) text-red-600 @else text-green-600 @endif">
                            {{ strtoupper(config('app.env')) }}
                        </p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Zona Horaria</p>
                        <p class="font-medium">{{ config('app.timezone') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Órdenes Hoy</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $ordersToday }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="{{ $ordersPercentageChange >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                        {{ $ordersPercentageChange >= 0 ? '+' : '' }}{{ $ordersPercentageChange }}% vs ayer
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Clientes Nuevos</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $newCustomersToday }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="{{ $customersPercentageChange >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                        {{ $customersPercentageChange >= 0 ? '+' : '' }}{{ $customersPercentageChange }}% vs semana
                        pasada
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ingresos Hoy</p>
                        <p class="text-2xl font-semibold text-gray-800">${{ number_format($revenueToday, 2) }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="{{ $revenuePercentageChange >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                        {{ $revenuePercentageChange >= 0 ? '+' : '' }}{{ $revenuePercentageChange }}% vs ayer
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Órdenes Pendientes</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $pendingOrders }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="{{ $pendingPercentageChange <= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                        {{ $pendingPercentageChange <= 0 ? '' : '+' }}{{ $pendingPercentageChange }} vs semana pasada
                    </span>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
