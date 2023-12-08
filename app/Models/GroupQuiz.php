<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'quiz_id'
    ];
}
