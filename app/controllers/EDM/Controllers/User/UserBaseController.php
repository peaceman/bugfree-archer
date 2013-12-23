<?php
namespace EDM\Controllers\User;

use BaseController;
use App;
use User;

class UserBaseController extends BaseController
{
	/**
	 * @var \User
	 */
	protected $user;

	public function __construct()
	{
		$this->beforeFilter('@fetchUser');
	}

	public function fetchUser($route)
	{
		$routeParameters = $route->parametersWithoutNulls();
		if (!isset($routeParameters['username'])) {
			App::abort(404);
		}

		$username = $routeParameters['username'];
		$user = User::where('username', '=', $username)->firstOrFail();
		$this->user = $user;

		App::make('view')->share('user', $user);
	}
}
