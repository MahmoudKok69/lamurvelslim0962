<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'invite_url',
        'teacher_id',
        'isPrivate',
        'isAvailable',
        'age',
        'institute',
        'description',
        'max_members',
        'count'
    ];

    public function users(){
        return $this->hasMany(User::class, 'group_id');
    }

    public function users_count(){
        return $this->hasMany(User::class, 'group_id')->count();
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function news(){
        return $this->hasMany(NewsPanel::class, 'group_id');
        
    }
}
