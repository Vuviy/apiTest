<?php

use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {



//    $r = \App\Models\User::query()->where('id', 5)->first();
//
//
//    dd($r->position);
    $offset = 30;
    $count = 2;
    $users = User::query()->offset($offset)->limit($count)->get();
//    $r =  User::query()->paginate(6);
    dd($users);

//    $rrr = \App\Models\User::query()->first();
//
//
//    $time = \Illuminate\Support\Carbon::now();
//
////    dd(strtotime($time));
//
//
//    dd( strtotime($rrr->created_at));


    return view('welcome');
});
