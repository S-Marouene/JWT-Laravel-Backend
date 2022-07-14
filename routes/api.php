<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    
    /* LOGIN */
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');

    /* USER CRUD */
    Route::get('user-profile', 'AuthController@userProfile');
    Route::delete('delete-user/{id}', 'UserController@delete');
    Route::post('update_user', 'UserController@update_user');
    Route::get('user-get', 'UserController@GetAllUser');

    /**GET USER BY TOKEN */
    Route::post('GetUserByToken', 'UserController@GetUserByToken');

    /**ROLE AND PERMISSION */
    Route::get('user-roles',  'UserController@GetPermissionByRole');

    /**  test */
    Route::post('ImgProfil_update', 'UserController@ImgProfil_update');
    

});



Route::group(['middleware' => ['super-admin'],
    'prefix' => 'sudo'],
    function () {
    Route::get('allSchools',  'SchoolsController@GetAllSchools');


 });