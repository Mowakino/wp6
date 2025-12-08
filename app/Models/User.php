<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * User → Recipes (favorites pivot)
     */
    public function favorites()
    {
        return $this->belongsToMany(Recipe::class, 'favorites')->withTimestamps();
    }


    /**
     * User → Comments (1-to-Many)
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

}
