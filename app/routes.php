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

Route::get(
	'categories/{categorySlugs}',
	['as' => 'shop-items.index.by-category', 'uses' => 'ShopItemController@getIndexByCategory']
)
	->where('categorySlugs', '[a-z-/]+');

Route::get(
	'items/{itemSlug}',
	['as' => 'shop-items.show', 'uses' => 'ShopItemController@getShow']
);

Route::group(
	['namespace' => 'EDM\Controllers\User', 'prefix' => 'users/{username}'],
	function () {
		Route::get('dashboard', ['as' => 'user.dashboard', 'uses' => 'DashboardController@show']);
		Route::get(
			'private-messages',
			['as' => 'user.private-messages', 'uses' => 'PrivateMessageController@getIndex']
		);

		Route::get('profile', ['as' => 'user.profile', 'uses' => 'ProfileController@getIndex']);
		Route::post('profile/password', ['as' => 'user.profile.perform.password', 'uses' => 'ProfileController@postChangePassword']);
		Route::post('profile/account', ['as' => 'user.profile.perform.account', 'uses' => 'ProfileController@postAccount']);
		Route::post('profile/basic', ['as' => 'user.profile.perform.basic', 'uses' => 'ProfileController@postBasic']);
		Route::post('profile/address', ['as' => 'user.profile.perform.address', 'uses' => 'ProfileController@postAddress']);
		Route::post('profile/payout-detail', ['as' => 'user.profile.perform.payout-detail', 'uses' => 'ProfileController@postPayoutDetail']);

		Route::resource('orders', 'OrderController', [
			'only' => ['index', 'store', 'show'],
			'names' => [
				'index' => 'users.orders.index',
				'create' => 'users.orders.create',
				'store' => 'users.orders.store',
				'show' => 'users.orders.show',
			]
		]);

		Route::group(['before' => 'qualifies-as-vendor'], function () {
			Route::resource('sales', 'SaleController', [
				'only' => ['index'],
				'names' => [
					'index' => 'users.sales.index',
				],
			]);

			Route::get('sales-conflicts', ['as' => 'user.sales-conflicts', 'uses' => 'SalesConflictsController@getIndex']);

			Route::get('items', ['as' => 'user.items', 'uses' => 'ItemController@getIndex']);
			Route::get('items/create', ['as' => 'user.items.create', 'uses' => 'ItemController@getCreate']);
			Route::get('items/{item_id}/edit', ['as' => 'user.items.edit', 'uses' => 'ItemController@getEdit']);
			Route::delete('items/{item_id}', ['as' => 'user.items.delete', 'uses' => 'ItemController@deleteDestroy']);
			Route::get(
				'customer-questions',
				['as' => 'user.customer-questions', 'uses' => 'CustomerQuestionController@getIndex']
			);
		});

		Route::get('', ['as' => 'user.public-profile', 'uses' => 'ProfileController@getPublicProfile']);
	}
);

Route::group(
	['namespace' => 'EDM\Controllers\Api', 'prefix' => 'api'],
	function () {
		Route::resource('shop-items', 'ShopItemController');

		Route::resource('shop-categories', 'ShopCategoryController');
		Route::resource('music-genres', 'MusicGenreController');
		Route::resource('music-programs', 'MusicProgramController');
		Route::resource('music-plugins', 'MusicPluginController');
		Route::resource('music-plugin-banks', 'MusicPluginBankController');
		Route::resource('resource-files', 'ResourceFileController');

		Route::get('uploads', 'UploadController@checkChunk');
		Route::post('uploads', 'UploadController@saveChunk');
	}
);

Route::group(
	['namespace' => 'EDM\Controllers\Admin', 'prefix' => 'admin'],
	function () {
		Route::get('', ['as' => 'admin.dashboard', 'uses' => 'DashboardController@show']);

		Route::resource('users', 'UserController');
		Route::resource('shop-items', 'ShopItemController');
		Route::resource('shop-categories', 'ShopCategoryController');

		Route::post('reviews/{reviewId}/start', ['as' => 'admin.reviews.start', 'uses' => 'ReviewController@postStart']);
		Route::resource('reviews', 'ReviewController');
		Route::resource('music-genres', 'MusicGenreController');
		Route::resource('music-programs', 'MusicProgramController');
		Route::resource('music-plugins', 'MusicPluginController');
		Route::resource('music-plugin-banks', 'MusicPluginBankController');
		Route::resource('resource-files', 'ResourceFileController');
		Route::resource('resource-locations', 'ResourceLocationController');
	}
);
