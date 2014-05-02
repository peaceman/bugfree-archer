<?php
namespace EDM\Controllers\User;
use View;

class SaleConflictController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.order-conflicts.index');
	}
}
