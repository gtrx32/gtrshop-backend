<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'paid_at',
        'transaction_code'
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function markPending(): self
    {
        $this->status = PaymentStatus::Pending;
        $this->paid_at = null;
        $this->save();
        return $this;
    }

    public function markPaid(string $transactionCode = null): self
    {
        $this->status = PaymentStatus::Paid;
        $this->paid_at = now();
        if ($transactionCode) {
            $this->transaction_code = $transactionCode;
        }
        $this->save();
        return $this;
    }

    public function markFailed(): self
    {
        $this->status = PaymentStatus::Failed;
        $this->save();
        return $this;
    }
}
