<div class="space-y-6">
    <!-- Card Principal -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <header class="border-b border-gray-700 pb-4 mb-6">
            <h1 class="text-xl font-bold text-purple-300 flex items-center">
                <i class="fas fa-file-excel mr-2"></i>
                Exportar Reportes de Stock
            </h1>
            <p class="text-gray-400 mt-1">Genera reportes en Excel de productos y variantes</p>
        </header>

        <!-- Filtros Comunes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Categoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Filtrar por Categoría" />
                <select wire:model.live="selectedCategory"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subcategoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Filtrar por Subcategoría" />
                <select wire:model="selectedSubcategory"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas las subcategorías</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Alerta de Stock -->
            <div class="flex items-end">
                <label class="inline-flex items-center mt-1">
                    <input type="checkbox" wire:model="stockAlertOnly"
                        class="rounded bg-gray-700 border-gray-600 text-purple-500 focus:ring-purple-500">
                    <span class="ml-2 text-gray-300">Solo con bajo stock</span>
                </label>
            </div>
        </div>

        <!-- Exportar Productos -->
        <section class="rounded-lg bg-gray-800 border border-gray-700 p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-box mr-2"></i>
                        Exportar Productos
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">Genera un reporte de todos los productos</p>
                </div>
                <x-button wire:click="exportProducts" class="bg-green-600 hover:bg-green-500">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar
                </x-button>
            </div>
        </section>

        <!-- Exportar Variantes -->
        <section class="rounded-lg bg-gray-800 border border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        Exportar Variantes
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">Genera un reporte de todas las variantes</p>
                </div>
                <x-button wire:click="exportVariants" class="bg-green-600 hover:bg-green-500">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar
                </x-button>
            </div>
        </section>
    </section>
</div>
