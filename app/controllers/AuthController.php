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

		// todo check state
		if (Auth::attempt($credentials, $remember)) {
			// todo flash message
			return Redirect::route('frontpage');
		} else {
			return Redirect::route('auth.log-in')->withErrors(['login' => 'Invalid credentials']);
		}
	}

	public function performLogOut()
	{
		Auth::logout();
		// todo flash message
		return Redirect::route('frontpage');
	}
}
