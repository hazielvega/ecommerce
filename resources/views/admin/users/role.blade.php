<div class="flex justify-between text-center">
    @if ($user->hasRole('admin'))
        Admin
    @else
        ------
    @endif

    {{-- Boton para cambiar de rol --}}
    <button
        class="underline text-blue-500 hover:no-underline" 
        wire:click="changeRole({{ $user->id }})">
        Cambiar ROL
    </button>
</div>