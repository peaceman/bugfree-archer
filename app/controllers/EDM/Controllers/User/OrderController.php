<?php
namespace EDM\Controllers\User;
use View;

class OrderController extends \BaseController
{
	public function getIndex()
	{
		return View::make('user.orders.index');
	}
}
