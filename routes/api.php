<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPermission;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Todo_listController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group( function () {

    Route::middleware([CheckPermission::class])->group(function(){
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::post('logout', [AuthController::class, 'logout']);    

    // Route::get('todo_list', [Todo_listController::class, 'index']);
    Route::resource('todo_lists', Todo_listController::class);
});
