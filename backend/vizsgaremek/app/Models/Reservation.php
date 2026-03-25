<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'restaurant_id',
        'table_id',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_count',
        'reservation_date',
        'reservation_time_only',
        'reservation_time',
        'party_size',
        'status',
        'notes',
        'confirmed_at',
    ];

    protected $casts = [
        'guest_count' => 'integer',
        'party_size' => 'integer',
        'reservation_time' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

