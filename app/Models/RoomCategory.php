<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    protected $table = 'roomcategory';

    protected $fillable = [
        'category_name',
        'category_description',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_category_id');
    }
}
