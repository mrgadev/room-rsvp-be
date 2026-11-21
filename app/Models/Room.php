<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category_id', 'name', 'image', 'description', 'price', 'total_units'])]
class Room extends Model
{
    use HasUuids;
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function transaction() {
        return $this->hasMany(Transaction::class);
    }
}
