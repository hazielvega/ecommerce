<div>
    {{-- Botón para abrir el modal de edición --}}
    <button wire:click="$set('open', true)" class="btn btn-indigo">
        Editar
    </button>

    {{-- Modal de edición --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Editar Categoría
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <x-label class="mb-1">
                    Nombre
                </x-label>

                <x-input class="w-full" wire:model="name"></x-input>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="space-x-3">
                <x-button class="ms-3" wire:click="updateCategory" wire:loading.attr="disabled">
                    Actualizar
                </x-button>

                <x-secondary-button wire:click="$set('open', false)" wire:loading.attr="disabled">
                    {{ __('Cerrar') }}
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
