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
		$musicGenres = \MusicGenre::all();
		$shopCategories = [];
		return View::make('user.items.create', compact('shopCategories', 'musicGenres'));
	}
}
