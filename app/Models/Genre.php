<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'genre'
    ];

    protected $dates = ['deleted_at'];
}
