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
        'name' => 'Editar Oferta',
    ],
]">

    @livewire('admin.offers.offer-edit', compact('offer'), key('offer-edit-' . $offer->id))

</x-admin-layout>