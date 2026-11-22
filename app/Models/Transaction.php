<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['room_id', 'user_id', 'total_price', 'checkin_date', 'checkout_date', 'status'])]
class Transaction extends Model
{
    use HasUuids;

    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
