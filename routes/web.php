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

Route::get('/log', function(){
    if (!isset($_SESSION)) session_start();
    if (isset($_SESSION['user']))
        return view('log');
    else
        return redirect()->route('main_page');
});

Route::get('/{page}/search', function(\Illuminate\Http\Request $request, $page){
    return view('search', ['page'=>$page]);
});
Route::get('/{page}/new', [ '\App\Http\Controllers\PageController', 'NewPage' ]);
Route::get('/{page}/{id}/edit', [ '\App\Http\Controllers\PageController', 'EditPage' ])->where('id', '[0-9]+');
Route::get('/{page}/{id}', [ '\App\Http\Controllers\PageController', 'SinglePage' ])->where('id', '[0-9]+');
Route::get('/{page}', [ '\App\Http\Controllers\PageController', 'ListPage' ]);
