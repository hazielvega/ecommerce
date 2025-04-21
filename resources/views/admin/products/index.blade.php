<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
    ],
]">

    {{-- Botón para agregar un nuevo producto --}}
    <x-slot name="action">
        <a href="{{ route('admin.products.create') }} " class="btn btn-indigo">
            Agregar producto
        </a>
    </x-slot>

    <div class="mb-6 card">
        @livewire('admin.products.product-index')
    </div>
    
    <div class="mb-6 card">
        @livewire('admin.offers.offer-index')
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Configuracion de precios --}}
        <div class="mb-6">
            @livewire('admin.products.product-prices')
        </div>
        {{-- Reporte de productos --}}
        <div class="mb-6">
            @livewire('admin.products.product-excel')
        </div>
    </div>

</x-admin-layout>
