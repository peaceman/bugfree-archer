<?php
namespace EDM\Common\Injections;

trait AuthManagerInjection
{
	/**
	 * @var \Illuminate\Auth\AuthManager
	 */
	protected $auth;

	public function injectAuthManager(\Illuminate\Auth\AuthManager $authManager)
	{
		$this->auth = $authManager;
	}
}
