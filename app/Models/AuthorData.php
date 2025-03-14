<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorData extends Model
{
    use HasFactory;

    protected $table = 'author_data'; // Указываем имя таблицы

    protected $fillable = [
        'club',
        'nickname',
        'age',
        'hobby',
        'email',
        'photo',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
}
