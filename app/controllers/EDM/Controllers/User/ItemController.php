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
		return View::make('user.items.form');
	}

	public function deleteDestroy($username, $itemId)
	{
		$shopItem = ShopItem::onlyFromOwner($this->user)
			->findOrFail($itemId);

		$shopItem->delete();
		\Notification::info(trans('user.items.notifications.deleted'));
		return \Redirect::route('user.items', ['username' => $this->user->username]);
	}

	public function getEdit($username, $itemId)
	{
		/** @var \ShopItem $shopItem */
		$shopItem = ShopItem::onlyFromOwner($this->user)
			->findOrFail($itemId);

		return View::make('user.items.form', [
			'stepData' => $shopItem->generateStepData(),
			'shopItem' => $shopItem,
		]);
	}
}
