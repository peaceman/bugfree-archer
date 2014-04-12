<?php
namespace EDM\Controllers\User;
use ShopItem;
use View;

class ItemController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.items.index', [
			'shopItems' => ShopItem::onlyFromOwner($this->user)->paginate(),
		]);
	}

	public function getCreate()
	{
		return View::make('user.items.create');
	}

	public function deleteDestroy($username, $itemId)
	{
		$shopItem = ShopItem::onlyFromOwner($this->user)
			->findOrFail($itemId);

		$shopItem->delete();
		\Notification::info(trans('user.items.notifications.deleted'));
		return \Redirect::route('user.items', ['username' => $this->user->username]);
	}
}
