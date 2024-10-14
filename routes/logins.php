<?php

use App\Http\Controllers\PublicAndResponder\ResponderController;
use App\Http\Controllers\UserAdminController;


// login  of qrt ako and admin
Route::post('/user-login',[UserAdminController::class, 'loginUser']);
Route::post('/admin-login',[UserAdminController::class, 'loginAdmin']);



//registration ito ng qrt ako
Route::post('/qrt-registration',[ResponderController::class, 'UsersRegistration']);

// public email registration
Route::post('/public-email-registration',[UserAdminController::class, 'PublicEmailRegistration']);

 //verify otp
 Route::post('/verify-otp',[UserAdminController::class, 'verifyOtp']);

 //resend otp
 Route::post('/resend-otp/{email}',[UserAdminController::class, 'sendOtp']);

//sa public logins
Route::middleware(['web'])->group(function () {
    Route::get('auth/{provider}', [UserAdminController::class, 'redirectToProvider']);
    Route::get('auth/{provider}/callback', [UserAdminController::class, 'handleProviderCallback']);
});





Route::controller(UserAdminController::class)->middleware('auth:sanctum')->group(function(){
    // to get details of the current users
    Route::get('/user-details', 'userDetails');
    Route::get('/admin-details', 'adminDetails');

    // admin and user logout
    Route::post('/user-logout', 'logoutUser');
    Route::post('/admin-logout', 'logoutAdmin');

    //for validate the token
    Route::get('/validate-token', 'validateToken');

    //change password for admins user like eoc lgu lce
    Route::post('/change-password','ChangePassword');


});
