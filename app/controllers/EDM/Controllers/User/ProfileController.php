<?php
namespace EDM\Controllers\User;
use View;

class ProfileController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.profile.index');
	}
}
