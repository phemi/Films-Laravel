<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $client;
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $url = env('API_BASE_URI').'films'.'?page='.$request->get('page', 1);
            $http = new \GuzzleHttp\Client();
            $response = $http->request('GET', $url);

            if ($this->confirmContentType($response)) {
                $json_response = json_decode($response->getBody());

                if ($json_response->hasError) {
                    return back()->with('custom_errors', $json_response->errors->details->message);
                }

                $data = $json_response->data;

                $result = new LengthAwarePaginator($data->data, $data->total, $data->per_page, $data->current_page);
                $result->setPath($request->path());

                return view('home')->with(['data'=>$result]);
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
            //return $this->handleError("HomeController::index()", $ex);
        }
        //return view('home');
    }

    public function viewFilm(Request $request, $film_slug){
        try{
            $url = env('API_BASE_URI').'films'.'/'.$film_slug;
            $http = new \GuzzleHttp\Client();
            $response = $http->request('GET', $url);

            if ($this->confirmContentType($response)) {
                $json_response = json_decode($response->getBody());

                if ($json_response->hasError) {
                    return back()->with('custom_errors', $json_response->errors->details->message);
                }

                $film = $json_response->data;

                return view('film')->with(['film'=>$film]);
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
            //return $this->handleError("HomeController::index()", $ex);
        }
    }
}
