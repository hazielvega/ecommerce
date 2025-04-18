<div>
    <x-select wire:change="updateStatus({{ $order->id }}, $event.target.value)">
        <option value="" disabled selected>Cambiar estado</option>
        {{-- Pendiente --}}
        <option value="{{ \App\Enums\OrderStatus::Pending->value }}" @selected($order->status === \App\Enums\OrderStatus::Pending)>
            Pendiente
        </option>
        {{-- Procesando --}}
        <option value="{{ \App\Enums\OrderStatus::Processing->value }}" @selected($order->status === \App\Enums\OrderStatus::Processing)>
            Procesando
        </option>
        {{-- Shipped --}}
        <option value="{{ \App\Enums\OrderStatus::Shipped->value }}" @selected($order->status === \App\Enums\OrderStatus::Shipped)>
            Enviado
        </option>
        {{-- Completed --}}
        <option value="{{ \App\Enums\OrderStatus::Completed->value }}" @selected($order->status === \App\Enums\OrderStatus::Completed)>
            Completado
        </option>
        {{-- Failed --}}
        <option value="{{ \App\Enums\OrderStatus::Failed->value }}" @selected($order->status === \App\Enums\OrderStatus::Failed)>
            Fallido
        </option>
        {{-- Refunded --}}
        <option value="{{ \App\Enums\OrderStatus::Refunded->value }}" @selected($order->status === \App\Enums\OrderStatus::Refunded)>
            Reembolsado
        </option>
        {{-- Cancelled --}}
        <option value="{{ \App\Enums\OrderStatus::Cancelled->value }}" @selected($order->status === \App\Enums\OrderStatus::Cancelled)>
            Cancelado
        </option>
    </x-select>
</div>
