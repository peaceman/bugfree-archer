<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', ['as' => 'frontpage', function()
{
	return View::make('home/index');
}]);

Route::get('auth/log-in', ['as' => 'auth.log-in', 'uses' => 'AuthController@showLogInForm']);
Route::post('auth/log-in', ['as' => 'auth.perform.log-in', 'uses' => 'AuthController@performLogin']);
Route::any('auth/log-out', ['as' => 'auth.log-out', 'uses' => 'AuthController@performLogOut']);
Route::get('users/sign-up', ['as' => 'user.sign-up', 'uses' => 'UserController@showSignUpForm']);
Route::post('users/sign-up', ['as' => 'user.perform.sign-up', 'uses' => 'UserController@performSignUp']);
