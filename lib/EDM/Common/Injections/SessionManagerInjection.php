<?php
namespace EDM\Common\Injections;

trait SessionManagerInjection
{
	/**
	 * @var \Illuminate\Session\SessionManager
	 */
	protected $session;

	public function injectSessionManager(\Illuminate\Session\SessionManager $sessionManager)
	{
		$this->session = $sessionManager;
	}
}
