<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Ожидание',
            self::Paid => 'Оплачено',
            self::Processing => 'Обработка',
            self::Shipped => 'Отправлено',
            self::Completed => 'Завершено',
            self::Cancelled => 'Отменено',
        };
    }
}
