<?php

Route::post('login', 'Http\Controllers\Api\AuthController@login');
Route::post('signup', 'Http\Controllers\Api\AuthController@signup');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('', 'Http\Controllers\Api\AuthController@show');
    Route::get('logout', 'Http\Controllers\Api\AuthController@logout');
});
