<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievedQuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'from',
        'to',
        'rate',
        'teacher_name'
    ];
}
