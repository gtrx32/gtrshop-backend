<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'total_quantity'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function addProduct(Product $product, int $quantity = 1): self
    {
        $cartItem = $this->cartItems()->firstWhere('product_id', $product->id);

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $this->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        $this->recalculate();

        return $this;
    }

    public function removeProduct(Product $product, int $quantity = null): self
    {
        $cartItem = $this->cartItems()->firstWhere('product_id', $product->id);

        if (!$cartItem) {
            return $this;
        }

        if ($quantity === null || $quantity >= $cartItem->quantity) {
            $cartItem->delete();
        } else {
            $cartItem->quantity -= $quantity;
            $cartItem->save();
        }

        $this->recalculate();

        return $this;
    }

    public function clear(): self
    {
        $this->cartItems()->delete();
        $this->total_quantity = 0;
        $this->total_price = 0;
        $this->save();
        return $this;
    }

    public function recalculate(): self
    {
        $this->load('cartItems.product');

        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($this->cartItems as $item) {
            $totalQuantity += $item->quantity;
            $totalPrice += $item->quantity * $item->product->price;
        }

        $this->total_quantity = $totalQuantity;
        $this->total_price = $totalPrice;
        $this->save();

        return $this;
    }
}
