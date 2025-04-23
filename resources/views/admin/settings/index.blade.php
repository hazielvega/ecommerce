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
        <section>
            @livewire('admin.options.manage-options')
        </section>

        {{-- Sección de Categorias --}}
        <section class="rounded-lg bg-gray-800 shadow-xl border border-gray-700">
            <header class="border-b border-gray-700 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-purple-300">Gestión de Categorías</h1>

                    @livewire('admin.categories.create-category')
                </div>
            </header>

            <div class="p-6 bg-gray-900 rounded-lg">
                @livewire('admin.categories.category-table')
            </div>
        </section>

        {{-- Sección de Subcategorias --}}
        <section class="rounded-lg bg-gray-800 shadow-xl border border-gray-700">
            <header class="border-b border-gray-700 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-purple-300">Gestión de Subcategorías</h1>

                    @livewire('admin.subcategories.subcategory-create')
                </div>
            </header>

            <div class="p-6 bg-gray-900 rounded-lg">
                @livewire('admin.subcategories.subcategory-table')
            </div>
        </section>

    </div>


</x-admin-layout>
