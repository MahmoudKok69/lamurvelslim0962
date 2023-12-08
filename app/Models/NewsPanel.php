<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPanel extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'teacher_id',
        'group_id'
    ];
}
