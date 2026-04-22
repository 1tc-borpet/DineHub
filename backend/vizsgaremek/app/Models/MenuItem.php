<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'description',
        'price',
        'image_url',
        'preparation_time',
        'is_available',
        'rating',
        'rating_count',
        'order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'preparation_time' => 'integer',
        'rating_count' => 'integer',
        'order' => 'integer',
    ];

    // Boot metódus: ha nincs restaurant_id, akkor a kategóriából vesszük
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->restaurant_id && $model->category_id) {
                $category = MenuCategory::find($model->category_id);
                if ($category) {
                    $model->restaurant_id = $category->restaurant_id;
                }
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'menu_item_id');
    }
}
