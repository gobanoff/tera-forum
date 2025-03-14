<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function author()
    {
        return $this->hasOne(Author::class);
    }
    public function comments()
    {

        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
    public function likes()
    {

        return $this->hasMany(Like::class, 'user_id', 'id');

    }
    public function dislikes()
    {

        return $this->hasMany(Dislike::class, 'user_id', 'id');
    }
    public function neutrals()
    {

        return $this->hasMany(Neutral::class, 'user_id', 'id');
    }
}
