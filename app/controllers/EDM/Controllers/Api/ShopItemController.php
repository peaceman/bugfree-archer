<?php
namespace EDM\Controllers\Api;

use App;
use EDM\ShopItem\Processors\CreateShopItem as CreateShopItemProcessor;
use EDM\ShopItem\Processors\UpdateShopItem as UpdateShopItemProcessor;

class ShopItemController extends BaseController
{
	public function store()
	{
		$inputData = $this->request->json('shop_item_data');

		/** @var CreateShopItemProcessor $createShopItemProcessor */
		$createShopItemProcessor = App::make(CreateShopItemProcessor::class);
		$result = $createShopItemProcessor->process($inputData);

		\Notification::success(trans('user.items.notifications.created'));
		return $this->response->json($result['shop_item']->toArray(), 201);
	}

	public function update($shopItemId)
	{
		$shopItem = \ShopItem::onlyFromOwner($this->user)
			->findOrFail($shopItemId);

		$inputData = $this->request->json('shop_item_data');

		/** @var UpdateShopItemProcessor $updateShopItemProcessor */
		$updateShopItemProcessor = App::make(UpdateShopItemProcessor::class);
		$result = $updateShopItemProcessor->process([
			'shop_item' => $shopItem,
			'input_data' => $inputData,
		]);

		\Notification::success(trans('user.items.notifications.updated'));
		return $this->response->json($result['shop_item']->toArray());
	}
}
