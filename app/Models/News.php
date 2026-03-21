<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'active_from',
    ];

    protected $casts = [
        'active_from' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $news) {
            if (empty($news->slug) && !empty($news->title)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
