<?php
namespace EDM\Controllers\User;
use View;

class OrderController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.orders.index');
	}
}
