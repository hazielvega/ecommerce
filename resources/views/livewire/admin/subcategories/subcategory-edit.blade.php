<div class="card">
    <form wire:submit="save">

        <x-validation-errors class="mb-4"></x-validation-errors>

        {{-- SELECT PARA LAS CATEGORIAS --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Categorías
            </x-label>

            <x-select name="category_id" wire:model.live="subcategoryEdit.category_id">
                <option value="" disabled>
                    Selecciona una categoría
                </option>

                @foreach ($this->categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        {{-- INPUT PARA EL NOMBRE DE LAS CATEGORIAS --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Nombre
            </x-label>

            <x-input class="w-full" placeholder="Nombre de la subcategoría" wire:model="subcategoryEdit.name">
            </x-input>
        </div>

        {{-- BOTON PARA ELIMINAR Y ACTUALIZAR --}}
        <div class="flex justify-end">
            <x-danger-button onclick="confirmDelete()">
                Eliminar
            </x-danger-button>

            <x-button class="ml-2">
                Actualizar
            </x-button>
        </div>
    </form>

    {{-- FORMULARIO PARA ELIMINAR --}}
    <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" id="delete-form">
        @csrf

        @method('DELETE')
    </form>

    {{-- ALERTA DE SWEETALERT PARA ELIMINAR --}}
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
</div>
