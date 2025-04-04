<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Subcategorías',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => 'Agregar',
    ],
]">

    @livewire('admin.products.product-create')

</x-admin-layout>
