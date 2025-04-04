<div>
    <x-select wire:change="updateStatus({{ $order->id }}, $event.target.value)">
        <option value="" disabled selected>Cambiar estado</option>
        <option value="{{ \App\Enums\OrderStatus::Pending->value }}" @selected($order->status === \App\Enums\OrderStatus::Pending)>
            Pendiente
        </option>
        <option value="{{ \App\Enums\OrderStatus::Processing->value }}" @selected($order->status === \App\Enums\OrderStatus::Processing)>
            Procesando
        </option>
        <option value="{{ \App\Enums\OrderStatus::Completed->value }}" @selected($order->status === \App\Enums\OrderStatus::Completed)>
            Completado
        </option>
        <option value="{{ \App\Enums\OrderStatus::Cancelled->value }}" @selected($order->status === \App\Enums\OrderStatus::Cancelled)>
            Cancelado
        </option>
    </x-select>
</div>
