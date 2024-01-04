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

//        $page = (int)1;
//        $offset = (int)0;
//        $count = (int)5;
//        if ($request->has('page')) {
//            $page = $request->page;
//        }
//
//        if ($request->has('offset') && $request->offset > 0) {
//            $offset = intval($request->offset);
//        }
//
//        if ($request->has('count')) {
//            $count = $request->count;
//        }
//
//        $fails = [];
//
//        if (!preg_match("/^[0-9]+$/", $count)) {
//            $fails['count'] =
//                ['The count must be an integer.'];
//        }
//
//        if ($page == 0) {
//            $fails['page'] =
//                ['The page must be at least 1.'];
//        }
//
//        if (!preg_match("/^[0-9]+$/", $page)) {
//            $fails['page'] =
//                ['The page must be an integer.'];
//        }
//
//        if (!empty($fails)) {
//
//            $data = [
//                'success' => false,
//                'message' => 'Validation failed',
//                'fails' => $fails
//            ];
//            return response()->json($data, 422);
//        }
//
//
//        $total_users = User::query()->count();
//        $total_pages = ceil(($total_users - $offset) / $count);
//        $offsetBd = 0 + $offset;
//
//        if ($page >= 2) {
//            $offsetBd = (($page - 1) * $count) + $offset;
//        }
//
//        if ($count > $total_users) {
//            $count = $total_users;
//        }
//
//        if ($page > $total_pages) {
//            return response()->json([
//                "success" => false,
//                "message" => 'Page not found',
//            ], 404);
//        }

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
