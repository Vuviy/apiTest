<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use GuzzleHttp\Client;
//use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function getToken(){

        $token = ApiToken::query()->create(['token' => Str::random(274)]);

        if($token){
            return response()->json([
                "success" => true,
                "token" => $token->token,
            ], 200);
        }
        return response()->json([
            "success" => false,
            "message" => 'Failed',
        ], 404);
    }
}
