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
        'name' => $category->name,
    ],
]">

    <div class="card">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <x-validation-errors class="mb-4"></x-validation-errors>

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                <x-input class="w-full" placeholder="Nombre de la categoría" name="name"
                    value="{{ old('name', $category->name) }}">
                </x-input>
            </div>

            <div class="flex justify-end">
                <x-danger-button onclick="confirmDelete()">
                    Eliminar
                </x-danger-button>

                <x-button class="ml-2">
                    Actualizar
                </x-button>
            </div>
        </form>
    </div>

    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" id="delete-form">
        @csrf

        @method('DELETE')
    </form>

    @push('js')
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "No podrás volver atrás",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar de todas formas",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form').submit();
                    }
                });
            }
        </script>
    @endpush
</x-admin-layout>