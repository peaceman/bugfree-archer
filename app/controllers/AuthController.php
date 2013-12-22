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

		$validLoginStates = [User::STATE_ACTIVE, User::STATE_TMP_BAN, User::STATE_PERMA_BAN];
		if (Auth::attempt($credentials, $remember) && in_array(Auth::user()->state, $validLoginStates)) {
			// todo flash message
			return Redirect::route('frontpage');
		} else {
			Auth::logout();
			return Redirect::route('auth.log-in')
				->withErrors(['login' => trans('flash.auth.invalid_credentials')]);
		}
	}

	public function performLogOut()
	{
		Auth::logout();
		// todo flash message
		return Redirect::route('frontpage');
	}
}
