<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Book extends Model{
    use HasFactory, Notifiable, HasUuids;
    protected $fillable = [
        'title',
        'author',
        'editorial',
        'genre',
        'description',
        'img_book',
        'date_published'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
