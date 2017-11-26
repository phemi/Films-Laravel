<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FilmController extends ApiController
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try{
            $film = Film::select('name','slug','description', 'country_id','rating', 'release_date', 'price', 'photo' )->with('country')->paginate(1);

            return $this->respondWithoutError($film);
        }catch(\Exception $ex){
            Log::error("FilmController::index()  ".$ex->getMessage());
            return $this->respondWithError(500,'Failed to retrieve film list','Something went wrong'.$ex->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try{
            //validate the post request
            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'slug'=> 'required|max:255|alpha_num|unique:films',
                'rating'=> 'required|integer|between:1,5',
                'release_date'=> 'required|Date',
                'price'=> 'required|regex:/^\d*(\.\d{1,2})?$/',
                'country_id'=> 'required|exists:countries,id',
                'photo' => 'mimes:jpeg,bmp,png'
            ]);

            //if validator fails return json error response
            if ($validator->fails()) {
                $msg = "";
                foreach($validator->errors()->toArray() as $error){
                    foreach($error as $errorMsg){
                        $msg .= "". $errorMsg . " " ;
                    }
                }
                return $this->respondWithError(404, 'Film creation failed', $msg);
            }

            //create film
            Film::create([
                'name'=>$request->get('name'),
                'slug'=>$request->get('slug'),
                'description'=>$request->get('description'),
                'rating'=>$request->get('rating'),
                'release_date'=>$request->get('release_date'),
                'price'=>$request->get('price'),
                'country_id'=>$request->get('country_id')
            ]);

            $response = [
                'message' => 'Film created!'
            ];

            return $this->respondWithoutError($response);
        }catch(\Exception $ex){
            Log::error("FilmController::store()  ".$ex->getMessage());
            return $this->respondWithError(404, 'Film creation failed','Something Went wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $film)
    {
        try{
            //I really dont like eloquent for this cos of performance issue. But for the sake of this test I would use it.
            //I prefer raw Sql query
            $film = Film::select('id','name','slug','description', 'country_id','rating', 'release_date', 'price', 'photo' )
                ->where('slug',$film)->first();

            if(!empty($film)){
                //load country, comment and user
                $film->load('country', 'comments.user', 'filmGenres.genre');
            }
            return $this->respondWithoutError($film);
        }catch(\Exception $ex){
            Log::error("FilmController::show()  ".$ex->getMessage());
            return $this->respondWithError(404, 'Film creation failed','Something Went wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function edit(Film $film)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Film $film)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function destroy(Film $film)
    {
        //
    }

    /**
     * handles comment on a film
     */
    public function comment(Request $request){
        try{
            //validate the post request
            $validator = Validator::make($request->all(), [

                'comment' => 'required',
                'film_id'=> 'required|exists:films,id'
            ]);

            //if validator fails return json error response
            if ($validator->fails()) {
                $msg = "";
                foreach($validator->errors()->toArray() as $error){
                    foreach($error as $errorMsg){
                        $msg .= "". $errorMsg . " " ;
                    }
                }
                return $this->respondWithError(404, 'Failed to comment', $msg);
            }

            //create film
            Comment::create([
                'comment'=>$request->get('comment'),
                'user_id'=>Auth::user()->id,
                'film_id'=>$request->get('film_id'),
            ]);

            $response = [
                'message' => 'Comment posted!'
            ];

            return $this->respondWithoutError($response);
        }catch(\Exception $ex){
            Log::error("FilmController::comment()  ".$ex->getMessage());
            return $this->respondWithError(404, 'Failed to comment','Something Went wrong');
        }
    }
}
