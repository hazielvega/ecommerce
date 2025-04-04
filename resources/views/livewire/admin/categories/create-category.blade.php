<div>
    {{-- Boton para abrir el modal de creacion --}}
    <div>
        <button wire:click="$set('open', true)" class="btn btn-indigo">
            Nueva Categoria
        </button>
    </div>

    {{-- Modal de creacion --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Nueva Categoria
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
                <x-button class="ms-3" wire:click="createCategory" wire:loading.attr="disabled">
                    Guardar
                </x-button>

                <x-secondary-button wire:click="$set('open', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
