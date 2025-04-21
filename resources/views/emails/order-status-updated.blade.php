@component('mail::message')
# Estado de tu pedido actualizado

El estado de tu pedido **#{{ $order->id }}** ha cambiado:

**De:** {{ \App\Enums\OrderStatus::tryFrom($oldStatus)?? 'Desconocido' }}  
**A:** {{ \App\Enums\OrderStatus::tryFrom($newStatus)?? 'Desconocido' }}

@component('mail::button', ['url' => route('orders.show', $order)])
Ver detalles del pedido
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent