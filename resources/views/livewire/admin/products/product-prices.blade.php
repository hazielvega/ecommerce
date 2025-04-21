<div class="space-y-6">
    <!-- Card Principal -->
    <section class="rounded-lg bg-gray-900 shadow-lg border border-gray-700 p-6">
        <header class="border-b border-gray-700 pb-4 mb-6">
            <h1 class="text-xl font-bold text-purple-300 flex items-center">
                <i class="fas fa-tags mr-2"></i>
                Ajuste Masivo de Precios
            </h1>
            <p class="text-gray-400 mt-1">Aplica cambios de precios por categoría o subcategoría</p>
        </header>

        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Categoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Categoría" />
                <select wire:model.live="selected_category"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subcategoría -->
            <div>
                <x-label class="text-gray-300 mb-1" value="Subcategoría" />
                <select wire:model="selected_subcategory"
                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-purple-500 focus:border-purple-500"
                    {{ !$selected_category ? 'disabled' : '' }}>
                    <option value="">Todas las subcategorías</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tipo de Ajuste -->
        <div class="mt-6">
            <x-label class="text-gray-300 mb-3" value="Tipo de Ajuste" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="adjustment_type" value="increase"
                        class="text-purple-500 focus:ring-purple-500 border-gray-600">
                    <span class="ml-2 text-gray-300">Aumentar Precios</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="adjustment_type" value="decrease"
                        class="text-purple-500 focus:ring-purple-500 border-gray-600">
                    <span class="ml-2 text-gray-300">Disminuir Precios</span>
                </label>
            </div>
        </div>

        <!-- Porcentaje -->
        <div class="mt-6">
            <x-label class="text-gray-300 mb-1" value="Porcentaje (%) *" />
            <div class="relative">
                <x-input wire:model="percentage" type="number" min="1" max="99"
                    class="w-full bg-gray-700 border-gray-600 text-white focus:ring-purple-500 pl-10"
                    placeholder="Ej. 10 para 10%" />
                <span class="absolute left-3 top-2.5 text-gray-400">%</span>
            </div>
            <x-input-error for="percentage" class="mt-1" />
        </div>

        <!-- Botón de Aplicar -->
        <div class="flex justify-end mt-6">
            <x-button wire:click="confirmPriceChange" wire:loading.attr="disabled"
                class="bg-purple-600 hover:bg-purple-500">
                <i class="fas fa-calculator mr-2"></i>
                Aplicar Ajuste
            </x-button>
        </div>
    </section>

    <!-- Modal de Confirmación -->
    @if ($showConfirmation)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700 max-w-md w-full p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-white">
                            Confirmar Ajuste de Precios
                        </h3>
                        <div class="mt-2 text-gray-300">
                            <p>¿Estás seguro que deseas aplicar un
                                <span
                                    class="font-bold">{{ $adjustment_type === 'increase' ? 'incremento' : 'decremento' }}</span>
                                del <span class="font-bold">{{ $percentage }}%</span> a los precios?
                            </p>
                            <p class="mt-2">Esta acción no se puede deshacer.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <x-button wire:click="$set('showConfirmation', false)" class="bg-gray-700 hover:bg-gray-600">
                        Cancelar
                    </x-button>
                    <x-button wire:click="applyPriceChange" class="bg-red-600 hover:bg-red-500">
                        <i class="fas fa-check mr-2"></i>
                        Confirmar
                    </x-button>
                </div>
            </div>
        </div>
    @endif
</div>
