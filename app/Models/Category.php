<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description'])]
class Category extends Model
{
    use HasUuids;
    public function rooms() {
        return $this->hasMany(Room::class);
    }
}
