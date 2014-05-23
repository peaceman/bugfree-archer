<?php
namespace EDM\ShopOrder\Processors;

use EDM\AbstractBaseProcessor;
use EDM\Common\Injections\LogWriterInjection;
use EDM\ShopOrder\Exception as PE; // PE -> ProcessorException

class CreateShopOrder extends AbstractBaseProcessor
{
	use LogWriterInjection;

	public function process(array $data = null)
	{
		/** @var $shopItem \ShopItem */
		$shopItem = $this->requireData($data, 'shop_item');
		/** @var $buyer \User */
		$buyer = $this->requireData($data, 'buyer');

		$this->ensureShopItemIsInABuyableState($shopItem);
		$this->ensureBuyerIsNotSeller($shopItem, $buyer);
		$this->ensureBuyerHasNotAlreadyBoughtTheShopItem($shopItem, $buyer);

		$shopOrder = new \ShopOrder;
		$shopOrder->shopItemRevision()->associate($shopItem->activeRevision);
		$shopOrder->userTrackingSession()->associate($buyer->fetchLastTrackingSession());
		$shopOrder->save();

		return $shopOrder;
	}

	protected function ensureShopItemIsInABuyableState(\ShopItem $shopItem)
	{
		if (!$shopItem->isInABuyableState()) {
			$this->log->error('shop item is not in a buyable state', [
				'context' => [
					'shop_item' => $shopItem->getAttributes(),
				],
			]);

			throw new PE\InvalidShopItemState($shopItem);
		}
	}

	protected function ensureBuyerIsNotSeller(\ShopItem $shopItem, \User $buyer)
	{
		if ($shopItem->isSeller($buyer)) {
			$seller = $shopItem->owner;

			$this->log->error('buyer is also seller of the shop item', [
				'context' => [
					'shop_item' => $shopItem->getAttributes(),
					'buyer' => $buyer->getAttributes(),
					'seller' => $seller->getAttributes(),
				]
			]);

			throw new PE\CannotBuyOwnShopItem($shopItem, $buyer, $seller);
		}
	}

	protected function ensureBuyerHasNotAlreadyBoughtTheShopItem(\ShopItem $shopItem, \User $buyer)
	{
		if ($buyer->hasAlreadyBoughtShopItem($shopItem)) {
			$this->log->error('buyer has already bought the shop item', [
				'context' => [
					'shop_item' => $shopItem->getAttributes(),
					'buyer' => $buyer->getAttributes(),
				],
			]);

			throw new PE\CannotBuyAShopItemMultipleTimes($shopItem, $buyer);
		}
	}
}
