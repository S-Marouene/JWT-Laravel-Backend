<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'AuthController@login');
    
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user-profile', 'AuthController@userProfile');

    Route::post('me', [App\Http\Controllers\AuthController::class, 'me']);

    Route::get('user-get', 'UserController@me');
    Route::delete('delete-user/{id}', 'UserController@delete');
    Route::put('update-user/{id}', 'UserController@update');
    
});
