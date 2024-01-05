<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionCollection;
use App\Http\Resources\UserResourceCollection;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function getAll()
    {

        $positions = Position::all();

        if(!$positions){
            return response()->json([
                "success" => false,
                "message" => 'Positions not found',
            ], 422);
        }

        return new PositionCollection($positions);

    }
}
