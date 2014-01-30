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

Route::filter('qualifies-as-vendor', EDM\User\Filters\QualifiesAsVendorFilter::class);

Route::get(
	'/',
	['as' => 'frontpage', function () {
		Notification::infoInstant('info test');
		Notification::successInstant('info test');
		Notification::errorInstant('error test');
		Notification::warningInstant('warning test');
		return View::make('home/index');
	}]
);

Route::get(
	'php.nfo',
	function () {
		phpinfo();
		exit;
	}
);

Route::get('error', ['as' => 'full-page-error', 'uses' => 'HomeController@showFullPageError']);
Route::get('start-selling', ['as' => 'start-selling', 'before' => 'qualifies-as-vendor', 'uses' => 'HomeController@startSelling']);

Route::get('auth/log-in', ['as' => 'auth.log-in', 'uses' => 'AuthController@showLogInForm']);
Route::post('auth/log-in', ['as' => 'auth.perform.log-in', 'uses' => 'AuthController@performLogin']);
Route::any('auth/log-out', ['as' => 'auth.log-out', 'uses' => 'AuthController@performLogOut']);
Route::get('users/sign-up', ['as' => 'user.sign-up', 'uses' => 'UserController@showSignUpForm']);
Route::post('users/sign-up', ['as' => 'user.perform.sign-up', 'uses' => 'UserController@performSignUp']);
Route::get(
	'users/confirm-email/{confirmationHash}',
	['as' => 'user.perform.email-confirmation', 'uses' => 'UserController@performEmailConfirmation']
);
Route::get(
	'users/resend-confirmation-email',
	['as' => 'user.resend-confirmation-email', 'uses' => 'UserController@showResendConfirmationEmail']
);
Route::post(
	'users/resend-confirmation-email',
	['as' => 'user.perform.resend-confirmation-email', 'uses' => 'UserController@performResendConfirmationEmail']
);

Route::group(
	['namespace' => 'EDM\Controllers\User'],
	function () {
		Route::get('users/{username}/dashboard', ['as' => 'user.dashboard', 'uses' => 'DashboardController@show']);
		Route::get(
			'users/{username}/private-messages',
			['as' => 'user.private-messages', 'uses' => 'PrivateMessageController@getIndex']
		);

		Route::get('users/{username}/profile', ['as' => 'user.profile', 'uses' => 'ProfileController@getIndex']);
		Route::post('users/{username}/profile/password', ['as' => 'user.profile.perform.password', 'uses' => 'ProfileController@postChangePassword']);
		Route::post('users/{username}/profile/account', ['as' => 'user.profile.perform.account', 'uses' => 'ProfileController@postAccount']);
		Route::post('users/{username}/profile/basic', ['as' => 'user.profile.perform.basic', 'uses' => 'ProfileController@postBasic']);
		Route::post('users/{username}/profile/address', ['as' => 'user.profile.perform.address', 'uses' => 'ProfileController@postAddress']);

		Route::group(['before' => 'qualifies-as-vendor'], function () {
			Route::get('users/{username}/orders', ['as' => 'user.orders', 'uses' => 'OrderController@getIndex']);
			Route::get('users/{username}/order-conflicts', ['as' => 'user.order-conflicts', 'uses' => 'OrderConflictController@getIndex']);

			Route::get('users/{username}/items', ['as' => 'user.items', 'uses' => 'ItemController@getIndex']);
			Route::get('users/{username}/items/create', ['as' => 'user.items.create', 'uses' => 'ItemController@getCreate']);
			Route::get(
				'users/{username}/customer-questions',
				['as' => 'user.customer-questions', 'uses' => 'CustomerQuestionController@getIndex']
			);
		});
	}
);

Route::group(
	['namespace' => 'EDM\Controllers\Api', 'prefix' => 'api'],
	function () {
		Route::resource('shop-categories', 'ShopCategoryController');
		Route::resource('music-genres', 'MusicGenreController');
		Route::resource('music-programs', 'MusicProgramController');
	}
);

Route::get('me/password', ['as' => 'user.password', 'uses' => 'UserController@showPasswordForm']);
