<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

Route::middleware('auth:sanctum')->group(function(){
    //return $request->user();
    Route::get('/threads', [ThreadController::class, 'index']);
    Route::post('/thread/create', [ThreadController::class, 'store']);
    Route::post('/thread/like/{thread_id}', [ThreadController::class, 'react']);
    Route::post('/thread/comment', [ThreadController::class, 'comment']);
    Route::post('/thread/subcomment', [ThreadController::class, 'subcomment']);
    Route::post('/thread/follow/{following_id}', [FriendController::class, 'follow_and_unfollow']);
});

Route::get('/test-auth', function () {
    if (auth('sanctum')->check()) {
        return response(['message' => 'User is authenticated', 'user' => auth()->user()]);
    } else {
        return response(['message' => 'User is not authenticated'], 401);
    }
})->middleware('auth:sanctum');


Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
