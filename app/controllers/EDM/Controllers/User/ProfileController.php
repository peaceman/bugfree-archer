<?php
namespace EDM\Controllers\User;

use View;
use BaseController;

class ProfileController extends BaseController
{
	public function getIndex()
	{
		return View::make('user.profile.index');
	}
}
