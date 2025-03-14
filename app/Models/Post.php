<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'slug', 'category', 'status', 'likes_count', 'dislikes_count', 'author_id'];

    public function author()
    {

        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
    public function comments()
    {

        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
    public function likes()
    {

        return $this->hasMany(Like::class, 'post_id', 'id');
    }
    public function dislikes()
    {

        return $this->hasMany(Dislike::class, 'post_id', 'id');

    }
    public function neutrals()
    {

        return $this->hasMany(Neutral::class, 'post_id', 'id');
    }
}
