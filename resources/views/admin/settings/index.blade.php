<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Configuraciones',
    ],
]">

    <div class="space-y-8">
        {{-- Sección de Opciones --}}
        <section class= "rounded-lg p-6 shadow-md">
            <h2 class="text-xl font-bold text-white mb-4">Opciones del Sistema</h2>
            <div class="rounded-md p-4">
                @livewire('admin.options.manage-options')
            </div>
        </section>

        {{-- Sección de Categorías --}}
        <section class="rounded-lg p-6 shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Gestión de Categorías</h2>
                <div class=" rounded-md p-2">
                    @livewire('admin.categories.create-category')
                </div>
            </div>

            <div class=" rounded-md overflow-hidden">
                @livewire('admin.categories.category-table')
            </div>
        </section>

        {{-- Sección de Subcategorías --}}
        <section class="rounded-lg p-6 shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Gestión de Subcategorías</h2>
                <div class=" rounded-md p-2">
                    @livewire('admin.subcategories.subcategory-create')
                </div>
            </div>

            <div class=" rounded-md overflow-hidden">
                @livewire('admin.subcategories.subcategory-table')
            </div>
        </section>
    </div>


</x-admin-layout>
