<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name','surname','email'];
    public function getPosts()
    {

        return $this->hasMany(Post::class, 'author_id', 'id');

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authorData()
    {
        return $this->hasOne(AuthorData::class, 'author_id', 'id');
    }


}
