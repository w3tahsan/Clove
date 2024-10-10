<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Author extends Authenticatable
{
    use HasFactory, Notifiable;

    function rel_to_post(){
        return $this->hasMany(Post::class, 'author_id');
    }

    protected $guarded  = ['id'];

    protected $guard = 'author';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
