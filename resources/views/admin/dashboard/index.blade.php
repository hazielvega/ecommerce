<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
]">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Tarjeta de Bienvenida -->
        <div
            class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl shadow-lg overflow-hidden border border-gray-700 transition-transform hover:scale-[1.02]">
            <div class="p-6 flex items-start">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full object-cover ring-2 ring-purple-500"
                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-purple-300">
                        Bienvenido, <span class="text-white">{{ auth()->user()->name }}</span>
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">
                        <i class="fas fa-clock mr-1 text-purple-400"></i>
                        Último acceso: {{ auth()->user()->last_login_at?->format('d/m/Y H:i') ?? 'Primer ingreso' }}
                    </p>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button
                            class="text-sm text-purple-400 hover:text-purple-300 flex items-center transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Información de la Empresa -->
        <div
            class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl shadow-lg overflow-hidden border border-gray-700 col-span-2 transition-transform hover:scale-[1.02]">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-white">
                            <i class="fas fa-building mr-2 text-purple-400"></i>
                            {{ config('app.name') }}
                        </h2>
                        <p class="text-sm text-gray-400 mt-1">{{ config('app.description') }}</p>
                    </div>
                    <span class="px-3 py-1 bg-purple-900 text-purple-300 text-xs font-medium rounded-full shadow">
                        v{{ config('app.version') }}
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="bg-gray-750 p-3 rounded-lg border border-gray-700">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Entorno</p>
                        <p class="font-medium mt-1 flex items-center">
                            @if (app()->environment('production'))
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                                <span class="text-red-400">PRODUCCIÓN</span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-green-400">{{ strtoupper(config('app.env')) }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-750 p-3 rounded-lg border border-gray-700">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Zona Horaria</p>
                        <p class="font-medium text-white mt-1 flex items-center">
                            <i class="far fa-clock mr-2 text-purple-400"></i>
                            {{ config('app.timezone') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Órdenes Hoy -->
        <div
            class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 transition-all hover:border-purple-500 hover:shadow-purple-500/10">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-900/30 text-blue-400">
                        <i class="fas fa-shopping-bag text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Órdenes Hoy</p>
                        <p class="text-2xl font-semibold text-white">{{ $ordersToday }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span
                        class="{{ $ordersPercentageChange >= 0 ? 'text-green-400' : 'text-red-400' }} text-sm font-medium flex items-center">
                        @if ($ordersPercentageChange >= 0)
                            <i class="fas fa-arrow-up mr-1"></i>
                        @else
                            <i class="fas fa-arrow-down mr-1"></i>
                        @endif
                        {{ abs($ordersPercentageChange) }}% vs ayer
                    </span>
                </div>
            </div>
        </div>

        <!-- Clientes Nuevos -->
        <div
            class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 transition-all hover:border-purple-500 hover:shadow-purple-500/10">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-900/30 text-green-400">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Clientes Nuevos</p>
                        <p class="text-2xl font-semibold text-white">{{ $newCustomersToday }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span
                        class="{{ $customersPercentageChange >= 0 ? 'text-green-400' : 'text-red-400' }} text-sm font-medium flex items-center">
                        @if ($customersPercentageChange >= 0)
                            <i class="fas fa-arrow-up mr-1"></i>
                        @else
                            <i class="fas fa-arrow-down mr-1"></i>
                        @endif
                        {{ abs($customersPercentageChange) }}% vs semana pasada
                    </span>
                </div>
            </div>
        </div>

        <!-- Ingresos Hoy -->
        <div
            class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 transition-all hover:border-purple-500 hover:shadow-purple-500/10">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-900/30 text-yellow-400">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Ingresos Hoy</p>
                        <p class="text-2xl font-semibold text-white">${{ number_format($revenueToday, 2) }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span
                        class="{{ $revenuePercentageChange >= 0 ? 'text-green-400' : 'text-red-400' }} text-sm font-medium flex items-center">
                        @if ($revenuePercentageChange >= 0)
                            <i class="fas fa-arrow-up mr-1"></i>
                        @else
                            <i class="fas fa-arrow-down mr-1"></i>
                        @endif
                        {{ abs($revenuePercentageChange) }}% vs ayer
                    </span>
                </div>
            </div>
        </div>

        <!-- Órdenes Pendientes -->
        <div
            class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 transition-all hover:border-purple-500 hover:shadow-purple-500/10">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-900/30 text-red-400">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Órdenes Pendientes</p>
                        <p class="text-2xl font-semibold text-white">{{ $pendingOrders }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span
                        class="{{ $pendingPercentageChange <= 0 ? 'text-green-400' : 'text-red-400' }} text-sm font-medium flex items-center">
                        @if ($pendingPercentageChange <= 0)
                            <i class="fas fa-arrow-down mr-1"></i>
                        @else
                            <i class="fas fa-arrow-up mr-1"></i>
                        @endif
                        {{ abs($pendingPercentageChange) }} vs semana pasada
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Sección adicional para gráficos o más estadísticas -->
    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-chart-bar mr-2 text-purple-400"></i>
                Resumen Semanal
            </h3>
            <div class="flex space-x-2">
                <button
                    class="px-3 py-1 text-xs bg-purple-900 text-purple-300 rounded-md hover:bg-purple-800 transition-colors">
                    Esta semana
                </button>
                <button
                    class="px-3 py-1 text-xs bg-gray-700 text-gray-400 rounded-md hover:bg-gray-600 transition-colors">
                    Este mes
                </button>
            </div>
        </div>

        <!-- Espacio para gráficos -->
        <div class="bg-gray-900 rounded-lg p-4 h-64 flex items-center justify-center border border-gray-700">
            <p class="text-gray-500 italic">[Gráfico de estadísticas semanales]</p>
        </div>
    </div> --}}

</x-admin-layout>
