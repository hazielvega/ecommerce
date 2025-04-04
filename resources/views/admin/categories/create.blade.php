<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'route' => route('admin.categories.index'),
    ],
    [
        'name' => 'Agregar',
    ],
]">

    <div class="card">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <x-validation-errors class="mb-4"></x-validation-errors>

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                <x-input class="w-full" placeholder="Nombre de la categoría" name="name" value="{{ old('name') }}">
                </x-input>
            </div>

            <div class="flex justify-end">
                <x-button>
                    Guardar
                </x-button>
            </div>
        </form>
    </div>

</x-admin-layout>
