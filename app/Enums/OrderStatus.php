<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 1;
    case Processing = 2;
    case Shipped = 3;
    case Completed = 4;
    case Failed = 5;
    case Refunded = 6;
    case Cancelled = 7;
}