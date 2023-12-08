<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievedAthkar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'athkar_id'
    ];
}
