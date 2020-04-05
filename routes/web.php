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
    return view('index');
});

Auth::routes(['verify' => true]);

Route::get('/dashboard', 'DashboardController@index');

Route::resource('terms','TermsController');
Route::get('/terms/{id}/publish','TermsController@publish');

Route::resource('users','UsersController');

Route::get('/users/unverify/{id}','UsersController@unverify');
Route::get('/users/search/{term}','UsersController@search');

Route::get('/users/search','UsersController@search');

Route::get('verify-email-first','Auth\RegisterController@verifyEmailFirst')->name('verify-email-first');

Route::get('/activateAccount/{email}/{verify_token}','Auth\RegisterController@activateAccount')->name('activateAccount');

Route::get('/resendActivationEmail/{email}','Auth\RegisterController@resendActivationEmail')->name('resendActivationEmail');
