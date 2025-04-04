{{-- Icono de pdf --}}
<button wire:click="downloadTicket({{ $order->id }})">
    <i class="fas fa-file-pdf text-2xl"></i>
</button>