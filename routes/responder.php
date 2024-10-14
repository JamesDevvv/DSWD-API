<?php
use App\Http\Controllers\PublicAndResponder\PublicController;
use App\Http\Controllers\PublicAndResponder\ResponderController;

Route::controller(PublicController::class)->prefix('public')->group(function(){
    //to create reports for public user
    Route::post('/create-report-public','CreateReport');
    //update public profile
    route::post('/update-public-profile/{id}','UpdateProfileDetails');
});








Route::controller(ResponderController::class)->prefix('qrt')->group(function(){
    //list of incident reports
    // Route::get('/incident-list','IncidentList');
    // // incident report details
    // Route::get('/incident-details/{id}','getIncidentDetails');
    // // incident report change status false alarm or positive
    // Route::get('/incident-change-status','changeStatus');
    //kinoment ko ito dahil di ko alam kung magagamit pa or hindi na...

    //Check in QRT member
    Route::post('check-in','CheckIn');
    
    //validataion
    Route::get('check-in-validation/{id}','CheckInValidation');

    // list of qrt on duty
    Route::get('on-duty','QrtOnDuty');

    //change password
    Route::post('change-password','ChangePassword');
});
