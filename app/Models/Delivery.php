<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'tracking_code',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'status' => DeliveryStatus::class,
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function markPending(): self
    {
        $this->status = DeliveryStatus::Pending;
        $this->shipped_at = null;
        $this->delivered_at = null;
        $this->save();
        return $this;
    }


    public function markShipped(string $trackingCode = null): self
    {
        $this->status = DeliveryStatus::Shipped;
        $this->shipped_at = now();
        if ($trackingCode) {
            $this->tracking_code = $trackingCode;
        }
        $this->save();
        return $this;
    }

    public function markDelivered(): self
    {
        $this->status = DeliveryStatus::Delivered;
        $this->delivered_at = now();
        $this->save();
        return $this;
    }

    public function markCancelled(): self
    {
        $this->status = DeliveryStatus::Cancelled;
        $this->save();
        return $this;
    }
}
