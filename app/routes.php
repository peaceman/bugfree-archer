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

Route::get('php.nfo', function () {
	phpinfo();
	exit;
});

Route::get('auth/log-in', ['as' => 'auth.log-in', 'uses' => 'AuthController@showLogInForm']);
Route::post('auth/log-in', ['as' => 'auth.perform.log-in', 'uses' => 'AuthController@performLogin']);
Route::any('auth/log-out', ['as' => 'auth.log-out', 'uses' => 'AuthController@performLogOut']);
Route::get('users/sign-up', ['as' => 'user.sign-up', 'uses' => 'UserController@showSignUpForm']);
Route::post('users/sign-up', ['as' => 'user.perform.sign-up', 'uses' => 'UserController@performSignUp']);
Route::get('users/confirm-email/{confirmationHash}', ['as' => 'user.perform.email-confirmation', 'uses' => 'UserController@performEmailConfirmation']);
Route::get('users/resend-confirmation-email', ['as' => 'user.resend-confirmation-email', 'uses' => 'UserController@showResendConfirmationEmail']);
Route::post('users/resend-confirmation-email', ['as' => 'user.perform.resend-confirmation-email', 'uses' => 'UserController@performResendConfirmationEmail']);

Route::get('me/dashboard', ['as' => 'user.dashboard', 'uses' => 'DashboardController@showHome']);
Route::get('me/dashboard/private-messages', ['as' => 'dashboard.private-messages', 'uses' => 'DashBoardController@showPrivateMessages']);
Route::get('me/dashboard/orders', ['as' => 'dashboard.order.history', 'uses' => 'DashBoardController@showOrders']);
Route::get('me/dashboard/order-conflicts', ['as' => 'dashboard.order.conflicts', 'uses' => 'DashBoardController@showOrderConflicts']);

Route::get('me/dashboard/items', ['as' => 'dashboard.items', 'uses' => 'DashBoardController@showItems']);
Route::get('me/dashboard/upload-item', ['as' => 'dashboard.item-upload', 'uses' => 'DashBoardController@showItemUpload']);
Route::get('me/dashboard/customer-questions', ['as' => 'dashboard.customer-questions', 'uses' => 'DashBoardController@showCustomerQuestions']);
Route::get('me/password', ['as' => 'user.password', 'uses' => 'UserController@showPasswordForm']);
