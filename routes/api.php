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

Route::post('/1c', function(Request $request){
    \Illuminate\Support\Facades\Storage::disk('local')->put(date('Y-m-d His').' '.time().'.json', json_encode($request->input()));
    return [ "status" => "success" ];
});

Route::post('/check_phone', [ '\App\Http\Controllers\UserController', 'CheckPhone' ])->name('check_phone');
Route::get('/user/{id}/delete', [ '\App\Http\Controllers\UserController', 'Delete' ])->where('id', '[0-9]+');
Route::get('/user/{id}/reset_password', [ '\App\Http\Controllers\UserController', 'ResetPassword' ])->where('id', '[0-9]+');

Route::post('/search/{page}', [ '\App\Http\Controllers\SearchController', 'Search' ]);
Route::post('/upload/{destination}/{name?}', [ '\App\Http\Controllers\FileController', 'Upload' ]);
Route::get('/images/{destination}/{file}/delete', [ '\App\Http\Controllers\FileController', 'Delete' ]);
Route::get('/media/exist/{name}', [ '\App\Http\Controllers\FileController', 'IsExist' ]);
Route::get('/media/get/all', [ '\App\Http\Controllers\FileController', 'GetJSON' ]);
Route::post('/promotion/special/edit', [ '\App\Http\Controllers\ContentController', 'PromSpecialEdit' ]);

Route::post('/service/{id}/move', [ '\App\Http\Controllers\ContentController', 'ServiceMove' ]);
Route::post('/{content}/add', [ '\App\Http\Controllers\ContentController', 'Add' ]);
Route::post('/{content}/delete_many', [ '\App\Http\Controllers\ContentController', 'Delete' ]);
Route::post('/{content}/restore_many', [ '\App\Http\Controllers\ContentController', 'Restore' ]);
Route::post('/performer/{id}/status/set', [ '\App\Http\Controllers\ContentController', 'PerformerStatusSet' ])->where('id', '[0-9]+');
Route::get('/{content}/{id}/get', [ '\App\Http\Controllers\ContentController', 'GetJSON' ])->where('id', '[0-9]+');
Route::post('/{content}/{id}/edit', [ '\App\Http\Controllers\ContentController', 'Edit' ])->where('id', '[0-9]+');
Route::post('/{content}/{id}/edit_field', [ '\App\Http\Controllers\ContentController', 'EditField' ])->where('id', '[0-9]+');
Route::get('/{content}/{id}/delete', [ '\App\Http\Controllers\ContentController', 'Delete' ])->where('id', '[0-9]+');
Route::get('/{content}/{id}/restore', [ '\App\Http\Controllers\ContentController', 'Restore' ])->where('id', '[0-9]+');
