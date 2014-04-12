<?php
namespace EDM\ControllerTraits;

trait RequireAuthenticatedUser
{
	/**
	 * @var \User authenticated user
	 */
	protected $user;

	public function fetchAuthenticatedUser()
	{
		if (!\Auth::check()) {
			\App::abort(403, 'Unauthorized access');
		}

		$this->user = \Auth::user();
	}
}
