<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'url',
        'product_id',
        'sort',
        'clicks',
        'text_color',
    ];

    protected $casts = [
        'sort' => 'integer',
        'clicks' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function click(): void
    {
        $this->increment('clicks');
    }

    public function getTargetUrlAttribute(): ?string
    {
        if (!empty($this->url)) {
            return $this->url;
        }

        if (!empty($this->product_id)) {
            return '/catalog/' . $this->product_id . '/';
        }

        return null;
    }
}
