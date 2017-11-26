<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * Override default login
     */
    public function login(Request $request)
    {
        try {
            //retrieve the fields needed from the request
            $data = $request->all();

            //validate the fields
            $validator = Validator::make($data, [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            //return with errors if validator fails
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            //make a request to the client api  and retrieve the response
            $url = env('API_BASE_URI').'login';
            $http = new \GuzzleHttp\Client();
            $response = $http->request('POST', $url, [
                'form_params' => [
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
                        ->with('toast', 'Login successful!')
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
            Log::error("LoginController::login: ". $e->getMessage());
            $message = 'Sorry an error occurred while trying to logging. Please Try again.' . $e->getMessage();
            return back()
                ->with('toast', $message)
                ->with('toast_level', 'danger');
        }
    }
}
