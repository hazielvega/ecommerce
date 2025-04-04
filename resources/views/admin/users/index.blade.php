<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
    ],
]">

    {{-- Llamo al datatable para mostrar los usuarios --}}
    @livewire('admin.users.user-table')

</x-admin-layout>
