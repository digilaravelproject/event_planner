<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subarea extends Model
{
    use HasFactory;

    protected $fillable = ['area_id', 'name'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
