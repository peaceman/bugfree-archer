<?php
namespace EDM\Controllers\Api;

use App;
use EDM\ShopItem\Processors\CreateShopItem as CreateShopItemProcessor;

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
}
