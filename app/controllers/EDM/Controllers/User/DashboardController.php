<?php
namespace EDM\Controllers\User;

use View;

class DashboardController extends UserBaseController
{
	public function show()
	{
		return View::make('user.dashboard.home');
	}
}
