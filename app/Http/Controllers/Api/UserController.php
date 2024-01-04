<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\ApiToken;
use App\Models\Position;
use App\Models\User;
use App\Services\ImageCropService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll(Request $request){

        $page = (int)1;
        $offset = (int)0;
        $count = (int)5;
        if ($request->has('page')){
            $page = $request->page;
        }

        if ($request->has('offset') && $request->offset > 0){
            $offset = intval($request->offset);
        }

        if ($request->has('count')){
            $count = $request->count;
        }

        $fails =[];

        if(!preg_match("/^[0-9]+$/", $count)){
            $fails['count'] =
                ['The count must be an integer.'];
        }

        if($page == 0){
            $fails['page'] =
                ['The page must be at least 1.'];
        }

        if(!preg_match("/^[0-9]+$/", $page)){
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


        $total_users =  User::query()->count();
        $total_pages =  ceil(($total_users - $offset)/$count);
        $offsetBd = 0 + $offset;

        if($page >= 2){
            $offsetBd = (($page - 1) * $count) + $offset;
        }

        if($count > $total_users){
            $count = $total_users;
        }

        if($page > $total_pages){
            return response()->json([
                "success" => false,
                "message" => 'Page not found',
            ], 404);
        }


        $users = UserResourceCollection::collection(User::query()->offset($offsetBd)->limit($count)->get());


        return response()->json([
            "success" => true,
            "page" => $page,
            "total_pages" => $total_pages,
            "total_users" => $total_users,
            "count" => intval($total_users - $offsetBd < $count ? $total_users - $offsetBd : $count),
            'links' => [
                "next_url" => $page != $total_pages ? url()->current().'?page='. $page +1 : null,
                "prev_url" => $page > 1 ? url()->current().'?page='. $page - 1 : null
            ],
            'users' => $users,
        ], 200);
    }

    public function get($id)
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
        return new UserResource($user);
    }





    public function create(UserRequest $request){

//        Validation token
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

        $data = $request->validated();

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

        if(!Position::query()->find($request->position_id)){
            $data = [
                'success' => false,
                'message' => 'Position with this id not exist',
            ];
            return response()->json($data, 404);
        }


        $cropService = new ImageCropService();
//        $file = $request->file('photo')->store('images');
        $file =  $cropService->save($request->file('photo'));
        $data['photo'] = $file;
        $user = User::query()->create($data);

        $data = [
            'success' => true,
            'user_id' => $user->id,
            'message' => 'New user successfully registered',
        ];
        return response()->json($data, 200);




    }
}
