<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function confirmContentType($response)
    {
        if ($response->getStatusCode() == 200) {
            if ($response->hasHeader('Content-Type') && $response->getHeader('Content-Type')[0] == 'application/json') {

                return true;
            }
        }
    }

    protected function setUserSession($user, $token){
        session()->regenerate();
        session(['token' => $token]);
        session(['user'=> $user]);
        session(['logged-in' => true]);
    }
}
