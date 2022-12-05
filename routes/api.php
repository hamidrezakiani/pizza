<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleActionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeasoningController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[LoginController::class, 'authenticate']);
Route::post('register',[RegisterController::class, 'register']);
Route::post('mobileVerification',[RegisterController::class, 'mobileVerification']);
Route::post('newVerificationCode',[RegisterController::class, 'newVerificationCode']);

Route::group(['middleware' => 'auth:api'],function(){
   Route::resource('categories', CategoryController::class);
   Route::resource('users',UserController::class);
   Route::resource('roles',RoleController::class);
   Route::post('userAttachRole',[UserRoleController::class,'attach']);
   Route::post('userDetachRole',[UserRoleController::class,'detach']);
   Route::get('userRoles',[UserRoleController::class,'index']);
   Route::post('roleAttachAction',[RoleActionController::class,'attach']);
   Route::post('roleDetachAction',[RoleActionController::class,'detach']);
   Route::get('roleActions',[RoleActionController::class,'index']);
   Route::resource('products',ProductController::class);
   Route::resource('seasonings',SeasoningController::class);
});

Route::get('artisan',function(){
    Artisan::call('cache:clear');
});

