<?php
namespace EDM\Controllers\User;
use View;

class PrivateMessageController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.private-messages.index');
	}
}
