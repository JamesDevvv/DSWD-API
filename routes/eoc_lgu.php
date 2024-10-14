<?php

use App\Http\Controllers\Admin\AdminSettings\QRTApprovalController;
use App\Http\Controllers\Admin\AdminSettings\RoleManagementController;
use App\Http\Controllers\Admin\AdminSettings\UserManagementController;
use App\Http\Controllers\Admin\ListController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\OperationController;
use App\Http\Controllers\Admin\StockPileController;


Route::controller(ListController::class)->prefix('reports')->group(function () {

    //pending report from public for validation tab
    Route::get('/incident-list', 'IncidentLists');
    //as of list
    Route::get('/as-of-list/{incident_code}', 'AsOfList');
    //get info
    Route::get('/archived-details/{id}', 'GetArchiveDetails');

    // list of reports by user -> used for total reports section on public
    Route::get('/reports-by-user/{id}','ReportsByUser');

    // badge percentage of public reports by user
    Route::get('/reports-percentage/{id}','ReportsByPercentage');

    // total count of DROMIC approval and Augmentation Request
    Route::get('/lead-summary','LeadSummary');



    // get details of info graphics
    Route::get('/get-info-details/{id}', 'GetReportIncidentDetatils');


    // list for roles
    Route::get('/role-list', 'GetRoles');


    //notification
    Route::get('/admin-notification-list', 'NotificationList');
});

Route::prefix('admin-settings')->group(function () {

    //ganto magging routing  server/api/admin-settings/user-management/route-name
    Route::controller(UserManagementController::class)->prefix('user-management')->group(function () {
        //user managemen list
        //need ko ng type if [admin,qrt,public]
        // sa search need ko ng fields
        Route::get('/admin-user-list', 'Lists');
        //get details
        Route::get('/get-detatils/{type}/{id}', 'GetDetatils');
        //edit function
        //need ko din ng type kung admin or qrt
        Route::post('/edit/{type}/{id}', 'Edit');

        //blocked unblock
        //need type kung admin or qrt or public
        Route::post('/toggle-block/{type}/{id}', 'ToggleBlock');
        //reset password to dswd2024
        //need type kung admin or qrt
        Route::post('/reset-password/{type}/{id}', 'ResetPassword');
    });


    Route::controller(QRTApprovalController::class)->prefix('qrt-approval')->group(function () {
        //list ng for approval
        Route::get('/qrt-list', 'Lists');

        //fetch info
        Route::get('/fetch-info/{id}', 'QrtDetails');

        //para sa buttons
        Route::post('/approve-or-reject/{id}', 'ApproveOrReject');


    });

    Route::controller(RoleManagementController::class)->prefix('role-management')->group(function () {
        //list
        Route::get('/role-list', 'Index');
        //
        Route::get('/fetch-info/{id}', 'GetInfo');

        //edit
        Route::post('/edit-role/{id}', 'Edit');

    });


    Route::controller(ListController::class)->prefix('user-logs')->group(function () {
        //lists
        Route::get('user-log-list', 'Index');
    });

});



Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
    // mga bilang o counts ng mga status ng incidents
    Route::get('/sumary-data', 'SummarizeStatusCounts');
    // map data pwede na din iapply yung filter feature
    Route::get('/map-data', 'MapsData');

    //field on staff counts or numbers of checked in
    Route::get('field-staff', 'FieldStaff');

    //field on staff list
    Route::get('field-staff-lists', 'FieldStaffList');
});

Route::controller(OperationController::class)->prefix('notification')->group(function () {

    // list of notification
    Route::get('/list', 'NotificationList');
    //for click of notification
    Route::put('/is-read/{id}', 'isRead');

    //for click of notification
    Route::put('/is-read/{id}', 'isRead');

});



Route::prefix('admin-actions')->group(function () {

    Route::controller(OperationController::class)->group(function () {

        // update status of reports
        Route::post('/update-reports/{id}', 'UpdateStatusIncident');
        // creating admin users
        Route::post('/create-admin-users', 'CreateAdminUsers');
        // creating new roles
        Route::post('/create-roles', 'createRoles');

        //create info graphics
        Route::post('/create-info-graphics', 'CreateReportIncident');
        //update reports info graphics
        Route::post('/update-info-graphics', 'UpdateReport');



        // forward to lgu kailangan ko yung role id dito sir
        Route::post('/forward-to-lgu', 'Forward2LGU');

        //approve augmentation
        Route::post('/augmentation-approval/{id}', 'ApprovedAugmentation');

        //update dromic status

        Route::post('/update-dromic-status/{id}','UpdateDromicStatus');


        // edit admin profile
        Route::post('/edit-admin-profile/{id}','EditProfile');

    });

    Route::controller(ListController::class)->group(function () {
        // need ng status
        Route::get('/augmentation-lists', 'AugmentationList');
    });

    Route::controller(StockPileController::class)->group(function () {
        // need lgu id or yung role id para sa lgu accounts
        Route::get('/stockfile-details', 'index');
        //pag lgu need mag pasa ng lgu id or yung role id
        //kung update need ng id if hindi empty or null lang yung id same as sa lgu_id
        Route::post('/create-update-stockpile', 'CreateOrUpdate');
    });

});
