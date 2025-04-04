<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Subcategorías',
        'route' => route('admin.subcategories.index'),
    ],
    [
        'name' => 'Agregar',
    ],
]">

    @livewire('admin.subcategories.subcategory-create')

</x-admin-layout>
