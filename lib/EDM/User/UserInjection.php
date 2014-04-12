<?php
namespace EDM\User;

trait UserInjection
{
	/**
	 * @var \User
	 */
	protected $user;

	public function injectUser(\User $user)
	{
		$this->user = $user;
	}
}
