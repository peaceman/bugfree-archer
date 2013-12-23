<?php
namespace EDM\Controllers\User;
use View;

class OrderConflictController extends \BaseController
{
	public function getIndex()
	{
		return View::make('user.order-conflicts.index');
	}
}
