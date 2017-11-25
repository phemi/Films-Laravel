<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilmGenres extends Model
{
    //
    use SoftDeletes;

    protected $table = "filmGenres";
    //
    protected $fillable = [
        'genre_id', 'film_id'
    ];
}
