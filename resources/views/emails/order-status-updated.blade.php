@component('mail::message')
# Estado de tu pedido actualizado

El estado de tu pedido **#{{ $order->id }}** ha cambiado:

**De:** {{ $oldStatus->name }}  
**A:** {{ $newStatus->name }}

@component('mail::button', ['url' => route('orders.show', $order)])
Ver detalles del pedido
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent