<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Ожидание',
            self::Shipped => 'Отправлено',
            self::Delivered => 'Доставлено',
            self::Cancelled => 'Отменено',
        };
    }
}
