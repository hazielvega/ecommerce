<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusNotification
{
    public function handle(OrderStatusUpdated $event)
    {
        $email = $event->order->user?->email ?? $event->order->receiver->email;
        
        Mail::to($email)->send(new OrderStatusUpdatedMail(
            $event->order,
            $event->oldStatus,
            $event->newStatus
        ));
    }
}