<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function getToken(){


        $token = ApiToken::query()->create(['token' => Str::random(274)]);

        if($token){
            return response()->json([
                "success" => true,
                "token" => $token->token,
            ]);
        }
        return response()->json([
            "success" => false,
            "message" => 'Failed',
        ], 404);
    }
}
