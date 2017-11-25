<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Film extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'name', 'slug', 'description', 'rating', 'release_date', 'price', 'country_id', 'genre_id', 'photo'
    ];

    protected $dates = ['deleted_at', 'release_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * Film has many comments
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * Film has more than one genre
     */
    public function genres()
    {
        return $this->hasManyThrough('App\Models\Genre','App\Models\FilmGenres');
    }
}
