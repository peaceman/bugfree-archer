<?php

class AuthController extends BaseController
{
	use \EDM\Common\Injections\AuthManagerInjection;
	use \EDM\Common\Injections\SessionManagerInjection;

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

			if ($this->session->has('url.intended')) {
				$intendedUrl = $this->replaceUsernamePlaceholderInUrl(
					$this->session->get('url.intended')
				);
				$this->session->set('url.intended', $intendedUrl);
			}

			return $this->redirector->intended(route('frontpage'));
		} else {
			$this->auth->logout();
			return $this->redirector->route('auth.log-in')
				->withErrors(['login' => trans('flash.auth.invalid_credentials')]);
		}
	}

	protected function replaceUsernamePlaceholderInUrl($url)
	{
		$urlWithReplacedPlaceholder = str_replace(
			urlencode('##USERNAME##'),
			urlencode($this->auth->user()->username),
			$url
		);
		return $urlWithReplacedPlaceholder;
	}

	public function performLogOut()
	{
		$this->auth->logout();
		Notification::info(trans('flash.auth.logout_successful'));
		return $this->redirector->route('frontpage');
	}
}
