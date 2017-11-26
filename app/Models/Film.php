<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Film extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'name', 'slug', 'description', 'rating', 'release_date', 'price', 'country_id', 'photo'
    ];

    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * Film has many comments
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment','film_id', 'id')->select('comment', 'created_at', 'name', 'film_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * Film has more than one genre
     */
    public function filmGenres()
    {
        return $this->hasMany('App\Models\FilmGenres', 'film_id', 'id')->select('film_id','genre_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country')->select('id','country_name');
    }
}
