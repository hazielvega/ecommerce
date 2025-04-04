<div>
    <div class="card mb-6">
        <h1 class="text-lg font-semibold mb-4 text-white">Cambiar precios de productos por grupo</h1>

        <!-- Categorías -->
        <div class="mb-4 grid grid-cols-4 items-center justify-between">
            <label for="category" class="text-white col-span-1">Categoría:</label>
            <x-select id="category" wire:model.live="selected_category" class="form-select col-span-3">
                <option value="">Todos</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-select>
        </div>

        <!-- Subcategorías -->
        <div class="mb-4 grid grid-cols-4 items-center justify-between">
            <label for="subcategory" class="text-white col-span-1">Subcategoría:</label>
            <x-select id="subcategory" wire:model.live="selected_subcategory" class="form-select col-span-3">
                <option value="">Todos</option>
                @foreach ($this->subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                @endforeach
            </x-select>
        </div>

        <!-- Tipo de ajuste (Aumento o Decremento) -->
        <div class="mb-4">
            <label class="text-white">Tipo de ajuste:</label>
            <div class="flex gap-4">
                <label class="text-white">
                    <input type="radio" wire:model="adjustment_type" value="increase"> Aumentar
                </label>
                <label class="text-white">
                    <input type="radio" wire:model="adjustment_type" value="decrease"> Disminuir
                </label>
            </div>
        </div>

        <!-- Porcentaje de ajuste -->
        <div class="mb-4">
            <label for="percentage" class="text-white">Porcentaje (%):</label>
            <input type="number" id="percentage" wire:model="percentage" class="form-input w-full" min="1"
                max="99" placeholder="Ingrese el porcentaje" />
            @error('percentage')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botón para aplicar el ajuste -->
        <div class="flex justify-end">
            <button wire:click="confirmPriceChange" class="btn btn-indigo">
                Aplicar ajuste
            </button>
        </div>
    </div>

    @push('js')
        <script>
            Livewire.on('confirmPriceChange', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "No podrás volver atrás",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aplicar ajuste",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('applyPriceChange');
                    }
                });
            })

            Livewire.on('priceChangeApplied', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Precios actualizados con exito',
                    showConfirmButton: false,
                    timer: 1500
                })
            })
        </script>
    @endpush
</div>
