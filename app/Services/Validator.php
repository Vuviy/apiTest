<?php

namespace App\Services;

use App\Models\ApiToken;
use App\Models\Position;
use App\Models\User;
use http\Client\Response;
use Illuminate\Http\Request;

class Validator
{

    public int $page = 1;
    public int $offset = 0;
    public int $count = 5;

    public function __construct(Request $request)
    {
        if ($request->has('page')){
            $this->page = $request->page;
        }

        if ($request->has('offset') && $request->offset > 0){
            $this->offset = intval($request->offset);
        }

        if ($request->has('count')){
            $this->count = $request->count;
        }
    }

    public function validateAllUsers()
    {
        $fails =[];

        if(!preg_match("/^[0-9]+$/", $this->count)){
            $fails['count'] =
                ['The count must be an integer.'];
        }

        if($this->page == 0){
            $fails['page'] =
                ['The page must be at least 1.'];
        }

        if(!preg_match("/^[0-9]+$/", $this->page)){
            $fails['page'] =
                ['The page must be an integer.'];
        }

        if(!empty($fails)){

            $data = [
                'success' => false,
                'message' => 'Validation failed',
                'fails' => $fails
            ];
            return response()->json($data, 422);
        }
    }


    public static function validateUser($id)
    {
        if(!preg_match("/^[0-9]+$/", $id)){
            $data = [
                'success' => false,
                'message' => 'Validation failed',
                'fails' => [
                    'user_id' => [
                        'The user_id must be an integer.'
                    ]
                ]
            ];
            return response()->json($data, 400);
        }
        $user = User::find($id);

        if(!$user){
            $data = [
                'success' => false,
                'message' => 'The user with the requested identifier does not exist',
                'fails' => [
                    'user_id' => [
                        'User not found'
                    ]
                ]
            ];
            return response()->json($data, 404);
        }
            return $user;
    }

    public static function checkToken(Request $request)
    {
        $token = $request->header('Token');

        if(!$token){
            $data = [
                'success' => false,
                'message' => 'The token not exist.'
            ];
            return response()->json($data, 401);
        }


        $token = ApiToken::query()->where('token', $token)->first();

        $created = strtotime($token->created_at);
        $end = $created + (40*60);
        $now = strtotime(now());
        $status = $now<$end;

        if(!$status){
            $data = [
                'success' => false,
                'message' => 'The token expired.'
            ];
            return response()->json($data, 401);
        }
//        Delete token
        $token->delete();
//        End of Validation token
    }


    public static function validateCreateUser($data)
    {
        $user = User::query()
            ->where('email', $data['email'])
            ->orWhere('phone', $data['phone'])
            ->first();

        if($user){
            $data = [
                'success' => false,
                'message' => 'User with this phone or email already exist',
            ];
            return response()->json($data, 409);
        }

        if(!Position::query()->find($data['position_id'])){
            $data = [
                'success' => false,
                'message' => 'Position with this id not exist',
            ];
            return response()->json($data, 404);
        }
    }
}
