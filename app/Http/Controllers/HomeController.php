<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

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
                    return back()->with('custom_errors', $json_response->errors->message);
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
                    return back()->with('custom_errors', $json_response->errors->message);
                }

                $film = $json_response->data;

                return view('film')->with(['film'=>$film]);
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
            //return $this->handleError("HomeController::index()", $ex);
        }
    }

    /**
     * @param Request $request
     * @param $film_slug
     * @return $this|\Illuminate\Http\RedirectResponse|string
     * Shows the create film form
     */
    public function createFilm(Request $request){
        try{
            $url = env('API_BASE_URI').'films/create';
            $http = new \GuzzleHttp\Client();
            $response = $http->request('GET', $url);

            if ($this->confirmContentType($response)) {
                $json_response = json_decode($response->getBody());

                if ($json_response->hasError) {
                    return back()->with('custom_errors', $json_response->errors->message);
                }

                $data = $json_response->data;

                return view('create_film')->with(['data'=>$data]);
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
            //return $this->handleError("HomeController::index()", $ex);
        }
    }

    public function storeFilm(Request $request){
        try{
            //validate the post request
            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'slug'=> 'required|max:255|alpha_num',
                'rating'=> 'required|integer|between:1,5',
                'release_date'=> 'required|Date',
                'price'=> 'required|regex:/^\d*(\.\d{1,2})?$/',
                'country_id'=> 'required',
                'photo' => 'mimes:jpeg,jpg,png'
            ]);

            //return with errors if validator fails
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            //make a request to the client api  and retrieve the response
            $url = env('API_BASE_URI').'films';
            $http = new \GuzzleHttp\Client();

            if($request->hasFile('photo')){
                $photo = $request->file('photo');
                $response = $http->request('POST', $url, [
                    'multipart' => [
                        [
                            'name' => 'photo',
                            'filename' => $photo->getClientOriginalName(),
                            'Mime-Type' => $photo->getClientMimeType(),
                            'contents' => fopen($photo->getPathname(), 'r'),
                        ],
                        [
                            'name' => 'name',
                            'contents' => $request->input('name')
                        ],
                        [
                            'name' => 'slug',
                            'contents' => $request->input('slug')
                        ],
                        [
                            'name' => 'description',
                            'contents' => $request->input('description')
                        ],
                        [
                            'name' => 'release_date',
                            'contents' => $request->input('release_date')
                        ],
                        [
                            'name' => 'price',
                            'contents' => $request->input('price')
                        ],
                        [
                            'name' => 'country_id',
                            'contents' => $request->input('country_id')
                        ],
                        [
                            'name' => 'rating',
                            'contents' => $request->input('rating')
                        ]
                    ]
                ]);
            }else{
                $response = $http->request('POST', $url, [
                    'form_params' => [
                        'name'=>$request->get('name'),
                        'slug'=>$request->get('slug'),
                        'description'=>$request->get('description'),
                        'rating'=>$request->get('rating'),
                        'release_date'=>$request->get('release_date'),
                        'price'=>$request->get('price'),
                        'country_id'=>$request->get('country_id')
                    ]
                ]);
            }



            //if response code is ok
            if ($response->getStatusCode() == 200) {
                // if the content type header if present
                //get json response and decode
                $json_response = json_decode($response->getBody());
                // the response has no custom error
                if (!$json_response->hasError) {
                    //log the user
                    return redirect()->intended('films');
                } else {

                    $msg = isset($json_response->errors->message)? $json_response->errors->message : "An error occurred, Please try again";
                    return back()
                        ->with('toast', $msg)
                        ->with('toast_level', 'warning');
                }
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
            //return $this->handleError("HomeController::index()", $ex);
        }


    }

    public function comment(Request $request){
        try{
            $data = $request->all();
            $validator = Validator::make($data, [
                'name'=>'required',
                'comment'=>'required',
                'film_id'=> 'required'
            ]);

            if ($validator->fails()) {
                $msg = "";
                foreach($validator->errors()->toArray() as $error){
                    foreach($error as $errorMsg){
                        $msg .= "". $errorMsg . " " ;
                    }
                }
                return response()->json([
                    'hasError'=> true,
                    'message' => $msg
                ]);
            }

            //send it over the api
            $url = env('API_BASE_URI').'comment';
            $http = new \GuzzleHttp\Client();
            $response = $http->request('POST', $url, [
                'form_params' => [
                    'name' =>$request->get('name'),
                    'comment'=>$request->get('comment'),
                    'film_id'=>$request->get('film_id'),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                // if the content type header if present
                //get json response and decode
                $json_response = json_decode($response->getBody());
                // the response has no custom error
                if (!$json_response->hasError) {
                    return response()->json([
                        'hasError'=> false,
                        'message' => 'Successfully posted your message'
                    ]);
                } else {
                    $msg = isset($json_response->errors->message)? $json_response->errors->message : "An error occurred, Please try again";
                    return response()->json([
                        'hasError'=> false,
                        'message' => $msg
                    ]);

                }
            }

        }catch(\Exception $ex){
            return response()->json([
                'hasError'=> true,
                'message' => 'An error occurred'
            ]);
        }
    }
}
