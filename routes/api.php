<?php


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

// route for admin and user log in

require base_path('routes/reference.php');
require base_path('routes/logins.php');

Route::group(['middleware'=>'auth:sanctum'], function() {
    require base_path('routes/responder.php');
    require base_path('routes/eoc_lgu.php');
    require base_path('routes/chat.php');
});
