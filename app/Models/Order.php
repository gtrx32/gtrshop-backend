<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'total_quantity',
        'status',
        'comment',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function markPending(): self {
        $this->status = OrderStatus::Pending;
        $this->save();
        return $this;
    }

    public function markPaid(): self {
        $this->status = OrderStatus::Paid;
        $this->save();
        return $this;
    }

    public function markProcessing(): self {
        $this->status = OrderStatus::Processing;
        $this->save();
        return $this;
    }

    public function markShipped(): self {
        $this->status = OrderStatus::Shipped;
        $this->save();
        return $this;
    }

    public function markCompleted(): self {
        $this->status = OrderStatus::Completed;
        $this->save();
        return $this;
    }

    public function markCancelled(): self {
        $this->status = OrderStatus::Cancelled;
        $this->save();
        return $this;
    }
}
