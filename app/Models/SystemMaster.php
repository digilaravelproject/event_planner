<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'label',
    ];
}
