<?php
class AuthController extends BaseController
{
	public function showLogInForm()
	{
		return View::make('common.login-signup-form');
	}

	public function performLogin()
	{
		$credentials = Input::get('login');

		$remember = false;
		if (array_key_exists('remember', $credentials)) {
			$remember = (bool)$credentials['remember'];
			unset($credentials['remember']);
		}

		if (Auth::attempt($credentials, $remember) && Auth::user()->isAllowedToLogin()) {
			Notification::success(trans('flash.auth.login_successful'));

			return Redirect::intended(URL::route('frontpage'));
		} else {
			Auth::logout();
			return Redirect::route('auth.log-in')
				->withErrors(['login' => trans('flash.auth.invalid_credentials')]);
		}
	}

	public function performLogOut()
	{
		Auth::logout();
		Notification::info(trans('flash.auth.logout_successful'));
		return Redirect::route('frontpage');
	}
}
