<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $page = 1;
        $count = 5;
        $offset = 0;

        $users = [];

        $prev = null;
        $next = null;


        if($request->get('page')){
            $page = $request->get('page');
            $count = $request->get('count') ? $request->get('count') : $count;
            $offset = $request->get('offset') ? $request->get('offset') : $offset;


            $total_users =  User::query()->count();
            $total_pages =  ceil(($total_users - $offset)/$count);
            if($page >= 2){
                $offsetBd = (($page - 1) * $count) + $offset;
            }

            if($count > $total_users){
                $count = $total_users;
            }

            $offsetBd = $offset;

            if($page >= 2){
                $offsetBd = (($page - 1) * $count) + $offset;
            }

            $users = User::query()->offset($offsetBd)->limit($count)->get();

            $base_url = url()->current();
            $next = $page != $total_pages ? $base_url.'?page='. $page +1 : null;
            $prev = $page > 1 ? $base_url.'?page='. $page - 1 : null;

            $next = $next ? $next.'&count='. $count : null;
            $prev = $prev ? $prev.'&count='. $count : null;

            $next = $next ? $offset > 0 ? $next.'&offset='. $offset : $next : null;
            $prev = $prev ? $offset > 0 ? $prev.'&offset='. $offset : $prev : null;

        }

        $links = ['prev' => $prev, 'next' => $next];

        $positions = Position::all();
        return view('__welcome', compact('positions', 'users', 'links'));
    }
}
