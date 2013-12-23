<?php
namespace EDM\Controllers\User;
use View;

class PrivateMessageController extends \BaseController
{
	public function getIndex()
	{
		return View::make('user.private-messages.index');
	}
}
