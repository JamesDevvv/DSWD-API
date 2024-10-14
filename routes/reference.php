<?php

use App\Http\Controllers\Reference\ReferenceController;


Route::controller(ReferenceController::class)->prefix('location')->group(function(){
    // for dropdowns
    Route::get('/district-list','MunicipalityCity');
    Route::get('/municipality-list/{code}','CityDistrict');
    Route::get('/barangay-list/{code}','Barangay');

    //disaster types
    Route::get('/disaster-types','DisasterType');

});

Route::controller(ReferenceController::class)->prefix('role')->group(function (){
    Route::get('/lgu-dropdowns','getLGU');

    Route::get('/lce-dropdown','lceList');
});
