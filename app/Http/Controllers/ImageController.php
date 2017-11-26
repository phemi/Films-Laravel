<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    public function getPost(Request $request,$image)
    {
        return response()->download(storage_path('app/films/'.$image),null,[],null);
    }
}
