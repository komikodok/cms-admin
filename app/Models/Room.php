<?php

namespace App\Models;

use App\Models\RoomImage;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function room_images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }
}
