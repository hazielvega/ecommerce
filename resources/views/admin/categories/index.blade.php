<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
    ],
]">


    {{-- Botón para agregar una nueva categoria --}}
    <x-slot name="action">
        @livewire('admin.categories.create-category')
    </x-slot>

    {{-- Lista de Categorias --}}
    @livewire('admin.categories.category-table')

</x-admin-layout>
