<?php

class HomeController extends BaseController
{

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{

		return View::make('hello');
	}

	public function showFullPageError()
	{
		if (!Session::has('full-page-error')) {
			return Redirect::route('frontpage');
		}

		$data = Session::get('full-page-error');
		return View::make('full-page-error')
			->with($data);
	}

	public function startSelling()
	{
		$user = Auth::user();
		return Redirect::route('user.items.create', ['username' => $user->username]);
	}

}
