<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification
{
    public function handle(OrderStatusUpdated $event)
    {
        // Log::info('SendOrderStatusNotification: Listener ejecutado', [
        //     'order_id' => $event->order->id,
        //     'old_status' => $event->oldStatus,
        //     'new_status' => $event->newStatus
        // ]);

        try {
            $email = $event->order->user->email ?? $event->order->receiver->email;
            
            // Log::info('Enviando email a: ' . $email);
            
            Mail::to($email)->send(new OrderStatusUpdatedMail(
                $event->order,
                $event->oldStatus,
                $event->newStatus
            ));
            
            // Log::info('Email enviado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al enviar email: ' . $e->getMessage());
        }
    }
}