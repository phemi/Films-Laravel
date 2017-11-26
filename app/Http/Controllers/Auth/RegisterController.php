<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/films';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Override the registration method
     */
    public function register(Request $request){
        try {
            //retrieve the fields needed from the request
            $data = $request->all();

            //validate the fields
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            //return with errors if validator fails
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            //make a request to the client api  and retrieve the response
            $url = env('API_BASE_URI').'register';
            $http = new \GuzzleHttp\Client();
            $response = $http->request('POST', $url, [
                'form_params' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password']
                ]
            ]);

            //if response code is ok
            if ($response->getStatusCode() == 200) {
                // if the content type header if present
                //get json response and decode
                $json_response = json_decode($response->getBody());
                // the response has no custom error
                if (!$json_response->hasError) {
                    //log the user
                    $this->setUserSession($json_response->data->user, $json_response->data->token);
                    return redirect()->intended('/')
                        ->with('toast', 'Your account has been created!')
                        ->with('toast_level', 'success')
                        ->with('email', $data['email']);
                } else {

                    $msg = isset($json_response->errors->message)? $json_response->errors->message : "An error occurred, Please try again";
                    return back()
                        ->with('toast', $msg)
                        ->with('toast_level', 'warning');
                }
            }
        } catch (\Exception $e) {
            Log::error("AuthController::register: ". $e->getMessage());
            $message = 'Sorry an error occurred while trying to create your account. Please Try again.';
            return back()
                ->with('toast', $message)
                ->with('toast_level', 'danger');
        }
    }

}
