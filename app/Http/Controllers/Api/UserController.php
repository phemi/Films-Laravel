<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    //
    /**
     * Register user
     */
    public function register(Request $request){

        try{
            //validate the post request
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:8',
            ]);

            //if validator fails return json error response
            if ($validator->fails()) {
                $msg = "";
                foreach($validator->errors()->toArray() as $error){
                    foreach($error as $errorMsg){
                        $msg .= "". $errorMsg . " " ;
                    }
                }
                return $this->respondWithError(404, 'User Registration failed', $msg);
            }

            //validation successful. Register user
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
            ]);

            //when it registers user it should automatically log user in
            $token = $user->createToken('film-manager')->accessToken;
            $response = [
                'message' => 'User successfully registered',
                'user' => $user->name,
                'token' => $token
            ];

            return $this->respondWithoutError($response);
        }catch(\Exception $ex){
            Log::error("UserController::register()  ".$ex->getMessage());
            return $this->respondWithError(404, 'User Registration failed','Something Went wrong');
        }
    }

    //Authenticate users
    public function authenticate(Request $request)
    {
        try{
            $data = $request->all();
            // grab credentials from the request
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($data, [
                'email'=>'required|email',
                'password' => 'required|min:8',
            ]);

            //if validator fails return json error responce
            if ($validator->fails()) {
                $msg = "";
                foreach($validator->errors()->toArray() as $error){
                    foreach($error as $errorMsg){
                        $msg .= "". $errorMsg . " " ;
                    }
                }
                return $this->respondWithError(404, 'User Registration failed', $msg);
            }

            // attempt to verify the credentials and create a token for the user
            if(Auth::attempt(['email'=>$request->get('email'), 'password' =>$request->get('password')])){
                $user = Auth::user();
                $token = $user->createToken('film-manager')->accessToken;
                // all good so return the token

                $data = ['token'=>$token, "user" => $user->name, "email"=>$user->email];
                return $this->respondWithoutError($data);
            }else{
                return $this->respondWithError(401,'Authentication Failed','Invalid email or password');
            }

        }catch (\Exception  $ex){
            Log::error("UserController::authenticate()  ".$ex->getMessage());
            return $this->respondWithError(500,'Authentication Failed','Something went wrong');
        }
    }
}
