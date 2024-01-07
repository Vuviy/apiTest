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
use App\Services\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class UserController extends Controller
{
    public function getAll(Request $request){

        $validator = new Validator($request);

        $validator->validateAllUsers();

        if(is_object($validator->validateAllUsers())){
            return $validator->validateAllUsers();
        }
        $page = $validator->page;
        $offset = $validator->offset;
        $count = $validator->count;

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

        $user = Validator::validateUser($id);

        if(get_class($user) !== 'App\Models\User'){
            return $user;
        }
        return new UserResource($user);
    }


    public function create(UserRequest $request){

        $invalidToken = Validator::checkToken($request);

        if($invalidToken){
            return $invalidToken;
        }

        $data = $request->validated();
        $invalid = Validator::validateCreateUser($data);

        if($invalid){
            return $invalid;
        }
        $cropService = new ImageCropService();
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
