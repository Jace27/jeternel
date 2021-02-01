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

Route::get('/', function (){
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['user'])) return redirect()->route('signin');
    return view('main');
})->name('main_page');
Route::get('/signin', function (){
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['user']))
        return view('signin');
    else
        return redirect()->route('main_page');
})->name('signin');
Route::post('/signin', [ '\App\Http\Controllers\UserController', 'SignIn' ]);
Route::get('/signout', [ '\App\Http\Controllers\UserController', 'SignOut' ]);

Route::get('/{page}/{id}', [ '\App\Http\Controllers\PageController', 'SinglePage' ]);
Route::get('/{page}', [ '\App\Http\Controllers\PageController', 'ListPage' ]);
