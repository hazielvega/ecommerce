<div>
    <h1 class="text-xl font-bold mb-5 text-white">Ventas</h1>
    <div class="card">
        <h1 class="text-lg font-semibold mb-4 text-white">Reportes de Ventas</h1>

        <div class="mb-4">
            <h2 class="text-white">Filtros</h2>

            {{-- Categorías --}}
            <div class="mb-4">
                <label for="category" class="text-white">Categoría:</label>
                <x-select id="category" wire:model.live="selected_category" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Sub-categorías --}}
            <div class="mb-4">
                <label for="subcategory" class="text-white">Subcategoría:</label>
                <x-select id="subcategory" wire:model.live="selected_subcategory" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($this->subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Fecha desde/ hasta --}}
            <div class="mb-4 flex space-x-3 items-center    ">
                <label for="date_range" class="text-white">Fechas:</label>
                <div class="flex flex-col">
                    <x-label for="date_from" class="text-white">Desde:</x-label>
                    {{-- desde --}}
                    <input type="date" id="date_from" wire:model.live="date_from" class="form-input">
                </div>

                <div class="flex flex-col">
                    <x-label for="date_to" class="text-white">Hasta:</x-label>
                    {{-- hasta --}}
                    <input type="date" id="date_to" wire:model.live="date_to" class="form-input">
                </div>
            </div>
        </div>

        {{-- Boton de generar reporte --}}
        <div class="mb-4">
            <button wire:click="generateReport" class="btn btn-green">
                Generar reporte
                <i class="fa-solid fa-file-excel"></i>
            </button>
        </div>
    </div>
</div>
