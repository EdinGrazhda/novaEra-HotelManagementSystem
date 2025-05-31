<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'name',
        'description',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
