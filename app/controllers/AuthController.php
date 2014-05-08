<?php
class AuthController extends BaseController
{
	use \EDM\Common\Injections\AuthManagerInjection;

	public function showLogInForm()
	{
		return $this->views->make('common.login-signup-form');
	}

	public function performLogin()
	{
		$credentials = $this->request->get('login');

		$remember = false;
		if (array_key_exists('remember', $credentials)) {
			$remember = (bool)$credentials['remember'];
			unset($credentials['remember']);
		}

		if ($this->auth->attempt($credentials, $remember) && $this->auth->user()->isAllowedToLogin()) {
			Notification::success(trans('flash.auth.login_successful'));

			return $this->redirector->intended(route('frontpage'));
		} else {
			$this->auth->logout();
			return $this->redirector->route('auth.log-in')
				->withErrors(['login' => trans('flash.auth.invalid_credentials')]);
		}
	}

	public function performLogOut()
	{
		$this->auth->logout();
		Notification::info(trans('flash.auth.logout_successful'));
		return $this->redirector->route('frontpage');
	}
}
