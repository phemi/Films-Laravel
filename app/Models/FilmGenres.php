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

    /*
     * Belongs to a genre
     */
    public function genre()
    {
        return $this->belongsTo('App\Models\Genre')->select('id','genre');
    }
}
