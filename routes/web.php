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

// Authentication Web Routes
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//social logins
Route::post('sociallogin/{provider}', 'AuthController@SocialSignup');
Route::get('auth/{provider}/callback', 'OutController@index')->where('provider', '.*');

Route::get('/send/email', 'AuthController@_welcomeEmail');

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');