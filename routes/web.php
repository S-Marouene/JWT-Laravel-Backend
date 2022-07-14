<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/***Create Table school */
Route::get('create_school', function () {
    
    if (!Schema::hasTable('schools')) {
        Schema::create('schools', function($table)
            {
                $table->increments('id');
                $table->string('Name', 50);
                $table->string('email', 30);
                $table->string('phone', 20);
                $table->string('address', 100);
                $table->timestamps();
            });
        return 'created succesfuly';
    }
    
    return 'table exist';
    
    
});
