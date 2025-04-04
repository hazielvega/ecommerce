<div>
    {{-- Boton para abrir el modal --}}
    <div class="">
        <x-button wire:click="$set('open', true)">
            Variantes
        </x-button>

        @if ($product->isOutOfStock())
            <span class="ml-2 bg-white text-red-600 font-bold px-2 py-1 rounded-full text-xs">
                {{-- Icono de signo de alerta --}}
                <i class="fas fa-exclamation-triangle"></i>
            </span>
        @endif
    </div>


    {{-- Modal de variantes --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Stock de Variantes
        </x-slot>

        <x-slot name="content">
            <ul class="grid grid-cols-1 lg:grid-cols-2 gap-4 uppercase">
                @foreach ($product->variants as $item)
                    <li class="rounded-lg bg-gray-900 p-4 text-white relative">
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <div class="col-span-2">
                                @foreach ($item->features as $feature)
                                    <p class="flex flex-col">
                                        <span class="px-3">
                                            {{ $feature['description'] }}
                                        </span>
                                    </p>
                                @endforeach
                            </div>

                            <div class="flex flex-col space-y-2 items-center">
                                {{-- Stock --}}
                                <span class="text-sm text-yellow-400">
                                    Stock: {{ $item->stock }}
                                </span>
                                {{-- Alerta de stock bajo --}}
                                @if ($item->isOutOfStock())
                                    <span class=" bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                        ¡Reabastecer!
                                    </span>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-slot>

        <x-slot name="footer">
            <div class="space-x-3">
                {{-- Boton editar Variantes --}}
                <x-button>
                    <a href="{{ route('admin.products.edit', $product) }}">Editar</a>
                </x-button>
                {{-- Boton para cerrar --}}
                <x-secondary-button wire:click="$set('open', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
