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
        'name' => $product->name,
    ],
]">


    <div class="mb-4">
        @livewire('admin.products.product-edit', compact('product'), key('product-edit-' . $product->id))
    </div>

    <div class="mb-4">
        @livewire('admin.products.product-variants', compact('product'), key('product-variants-' . $product->id))
    </div>

    <div class="mb-4">
        @livewire('admin.products.product-offer-create', compact('product'), key('product-offer-create-' . $product->id))
    </div>


</x-admin-layout>