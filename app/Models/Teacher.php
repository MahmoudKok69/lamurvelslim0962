<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Teacher extends Authenticatable implements JWTSubject
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'gender',
        'photo',
        'address'
    ];

    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    
    public function groups(){
        $id = $this->hasMany(Group::class, 'teacher_id');
        return $id->select('id');
    }

    public function createdGroups(){
        return $this->hasMany(Group::class, 'teacher_id');
    }

    public function news_panel(){
        return $this->hasMany(NewsPanel::class, 'teacher_id');
    }

    public function createdQuizzes(){
        return $this->hasMany(Quiz::class, 'teacher_id');
    }
}
