<?php
namespace EDM\Controllers\User;

use BaseController;
use App;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Environment as ViewFactory;
use User;

class UserBaseController extends BaseController
{
	/**
	 * @var \User
	 */
	protected $user;

	public function __construct(Response $response, Redirector $redirector, Request $request, ViewFactory $view)
	{
		parent::__construct($response, $redirector, $request, $view);
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
