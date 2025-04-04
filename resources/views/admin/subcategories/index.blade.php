<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Subcategorías',
    ],
]">


    {{-- Botón para agregar una nueva subcategoría --}}
    <x-slot name="action">
        @livewire('admin.subcategories.subcategory-create')
    </x-slot>
    

    {{-- Llamo al datatable para mostrar las subcategorías --}}
    @livewire('admin.subcategories.subcategory-table')

</x-admin-layout>


