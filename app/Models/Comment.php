<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'comment', 'user_id', 'film_id', 'name'
    ];

    protected $dates = ['deleted_at'];

    /*
     * belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->select('id','name');
    }
}
