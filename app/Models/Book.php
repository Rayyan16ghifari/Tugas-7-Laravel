<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'genre_id',
        'published_year',
        'description',
        'price'
    ];

    // Relasi: satu buku punya satu author
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // Relasi: satu buku punya satu genre
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    // Relasi: satu buku bisa muncul di banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
