<?php
namespace EDM\Controllers\User;
use View;

class ItemController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.items.index');
	}

	public function getCreate()
	{
		return View::make('user.items.create');
	}
}
