<div>
    <div class="card">
        <h1 class="text-lg font-semibold mb-4 text-white">
            Reporte de stock de productos
        </h1>
        {{-- Productos --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Productos
            </x-label>

            <div class="grid grid-cols-6 gap-4">
                <x-select wire:model="filter" class="col-span-5">
                    <option value="all">Todos los productos</option>
                    {{-- <option value="low_stock">Todos los productos con bajo stock</option> --}}
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">Productos de {{ $category->name }}</option>
                        {{-- <option value="{{ $category->id }}_low_stock">Productos de {{ $category->name }} con bajo stock</option> --}}
                    @endforeach
                </x-select>

                <a wire:click="exportProducts" class="btn btn-green hover:cursor-pointer text-center">
                    <i class="fas fa-file-excel text-lg ml-2"></i>
                </a>
            </div>
        </div>

        {{-- Variantes --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Variantes
            </x-label>

            <div class="grid grid-cols-6 gap-4">
                <x-select wire:model="filter" class="col-span-5">
                    <option value="all">Todas las variantes</option>
                    <option value="low_stock">Variantes con bajo stock</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">Variantes de {{ $category->name }}</option>
                        <option value="{{ $category->id }}_low_stock">Variantes de {{ $category->name }} con bajo stock
                        </option>
                    @endforeach
                </x-select>

                <a wire:click="exportVariants" class="btn btn-green hover:cursor-pointer text-center">
                    <i class="fas fa-file-excel text-lg ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>
