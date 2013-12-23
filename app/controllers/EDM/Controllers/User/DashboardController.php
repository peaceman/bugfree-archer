<?php
namespace EDM\Controllers\User;

use View;

class DashboardController extends \BaseController
{
	public function show($username)
	{
		return View::make('user.dashboard.home');
	}
}
