<div>
    {{-- Botón para abrir el modal de creación --}}
    <x-button wire:click="$set('open', true)" class="btn btn-indigo">
        <i class="fas fa-plus mr-2"></i> Nueva Subcategoría
    </x-button>

    {{-- Modal de creación --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Nueva Subcategoría
        </x-slot>

        <x-slot name="content">
            <x-validation-errors class="mb-4"></x-validation-errors>

            {{-- SELECT PARA LAS CATEGORÍAS --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Categoría
                </x-label>

                <x-select class="w-full" wire:model="subcategory.category_id">
                    <option value="" disabled>
                        Selecciona una categoría
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            {{-- INPUT PARA EL NOMBRE DE LA SUBCATEGORÍA --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                <x-input class="w-full" placeholder="Nombre de la subcategoría" wire:model="subcategory.name">
                </x-input>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="space-x-3">
                <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                    Guardar
                </x-button>

                <x-secondary-button wire:click="$set('open', false)" wire:loading.attr="disabled">
                    Cerrar
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
