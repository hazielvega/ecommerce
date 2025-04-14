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
                        <p class="text-2xl font-semibold text-gray-800">24</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">+12% vs ayer</span>
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
                        <p class="text-2xl font-semibold text-gray-800">8</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">+33% vs semana pasada</span>
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
                        <p class="text-2xl font-semibold text-gray-800">$12,450</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">+5% vs ayer</span>
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
                        <p class="text-2xl font-semibold text-gray-800">5</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-red-600 text-sm font-medium">-2 vs semana pasada</span>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Acciones Rápidas -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 mb-8">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.orders.create') }}"
                    class="group flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Nueva Orden</span>
                </a>

                <a href="{{ route('admin.products.create') }}"
                    class="group flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-green-50 transition-colors">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 group-hover:bg-green-200 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Agregar Producto</span>
                </a>

                <a href="{{ route('admin.reports.sales') }}"
                    class="group flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-purple-50 transition-colors">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 group-hover:bg-purple-200 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Reportes</span>
                </a>

                <a href="{{ route('admin.settings') }}"
                    class="group flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-yellow-50 transition-colors">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Configuración</span>
                </a>
            </div>
        </div>
    </div> --}}

    {{-- <!-- Últimas Órdenes -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Últimas Órdenes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Ver
                    todas</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliente</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->user->name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($order->status->value === 1) bg-yellow-100 text-yellow-800
                                    @elseif($order->status->value === 4) bg-green-100 text-green-800
                                    @elseif($order->status->value === 5) bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                        {{ $order->status->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

</x-admin-layout>
