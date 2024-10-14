<?php

use App\Http\Controllers\ChatController;

Route::controller(ChatController::class)->prefix('chat')->group(function () {

    Route::post('/create-find','createOrFindChat');
    Route::post('/send-message','sendMessage');
    Route::get('/chat-lists','listUserChats');
    //chat id yung need
    Route::get('/conversation/{id}','conversation');
    Route::get('/user-lists','allUserList');

    //is seen
    Route::post('/is-seen/{id}','is_seen');
});
