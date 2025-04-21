<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => 'Crear Oferta',
    ],
]">

    @livewire('admin.offers.offer-create', key('offer-create'))

</x-admin-layout>