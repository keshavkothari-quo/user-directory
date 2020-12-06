<?php

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

Route::get('login', 'AuthController@index');
Route::post('post-login', 'AuthController@postLogin');
Route::get('register', 'AuthController@register');
Route::post('post-register', 'AuthController@postRegister');
Route::get('logout', 'AuthController@logout');
Route::get('forget', 'AuthController@forgetPassword');
Route::post('forget', 'AuthController@sendForgetPassword');
Route::get('reset-password', 'AuthController@resetPassword');
Route::post('reset-password-link', 'AuthController@updatePassword');

Route::middleware('authLogin')->group(function (){
    Route::get('dashboard', 'ProfileDetailController@dashboard');
    Route::post('save-profile/', 'ProfileDetailController@saveProfileDetail');
    Route::get('edit-profile/{userId}', 'ProfileDetailController@getProfileDetail');
    Route::post('edit-profile/', 'ProfileDetailController@editProfileDetail');
    Route::get('contact-list/{userId}', 'ContactsController@getContactList');
    Route::get('search-contact', 'ContactsController@seachContactList');
    Route::get('reset-password/{userId}', 'ProfileDetailController@resetPassword');
    Route::post('reset-password', 'ProfileDetailController@updatePassword');
});

