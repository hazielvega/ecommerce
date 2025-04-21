<?php

namespace App\Observers;

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    public function updating(Order $order)
    {
        if ($order->isDirty('status')) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $order->getOriginal('status'),
                'to_status' => $order->status,
                'changed_by' => Auth::id(),
            ]);
        }
    }
}
