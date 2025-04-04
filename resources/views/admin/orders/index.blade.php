<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ordenes',
    ],
]">

    {{-- Llamo al datatable para mostrar todas las ordenes --}}
    @livewire('admin.orders.order-table')

</x-admin-layout>