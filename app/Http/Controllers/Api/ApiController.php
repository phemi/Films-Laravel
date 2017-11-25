<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    //
    protected $statusCode = 200;
    protected $user;

    public function __construct() {

    }


    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respondWithoutError($data) {

        $response = [
            'hasError' => false,
            'data' => $data,
        ];

        return response()->json($response);
    }

    protected function respondWithError($errorCode, $title, $errorMessage) {
        return response()->json([
            'hasError' => true,
            'errors' => [
                'code' => $errorCode,
                'title' => $title,
                'message' => $errorMessage
            ]
        ]);
    }

    protected function sayHello(){
        return $this->respondWithoutError("Hello Film-Manager");
    }
}
