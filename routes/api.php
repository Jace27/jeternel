<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/check_phone', [ '\App\Http\Controllers\UserController', 'CheckPhone' ])->name('check_phone');
Route::get('/user/{id}/delete', [ '\App\Http\Controllers\UserController', 'Delete' ])->where('id', '[0-9]+');
Route::get('/user/{id}/reset_password', [ '\App\Http\Controllers\UserController', 'ResetPassword' ])->where('id', '[0-9]+');

Route::post('/search/service', [ '\App\Http\Controllers\SearchController', 'Search' ]);

Route::post('/{content}/add', [ '\App\Http\Controllers\ContentController', 'Add' ]);
Route::post('/{content}/{id}/edit_field', [ '\App\Http\Controllers\ContentController', 'EditField' ])->where('id', '[0-9]+');
Route::get('/{content}/{id}/delete', [ '\App\Http\Controllers\ContentController', 'Delete' ])->where('id', '[0-9]+');
