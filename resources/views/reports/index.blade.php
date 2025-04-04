<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Informes',
    ],
]">

    <div class="mb-4">
        @livewire('admin.sales')
    </div>

    <div class="mb-4">
        @livewire('admin.reports')
    </div>

</x-admin-layout>
