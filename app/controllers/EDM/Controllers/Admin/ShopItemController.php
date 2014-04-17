<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class ShopItemController extends AuthenticatedBaseController
{
	public function index()
	{
		$shopItems = \ShopItem::query()
			->orderBy('updated_at', 'desc')
			->paginate();

		return $this->response->view('admin.shop-item.index', [
			'shopItems' => $shopItems,
			'amountOfShopItems' => \ShopItem::count(),
		]);
	}
}
