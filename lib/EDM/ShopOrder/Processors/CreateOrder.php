<?php
namespace EDM\ShopOrder\Processors;

use EDM\AbstractBaseProcessor;
use EDM\Common\Injections\LogWriterInjection;
use EDM\ShopOrder\Exception as PE; // PE -> ProcessorException

class CreateOrder extends AbstractBaseProcessor
{
	use LogWriterInjection;

	public function process(array $data = null)
	{
		/** @var $shopItem \ShopItem */
		$shopItem = $this->requireData($data, 'shop_item');
		/** @var $buyer \User */
		$buyer = $this->requireData($data, 'buyer');
		/** @var $seller \User */
		$seller = $shopItem->owner;

		$this->ensureShopItemIsInABuyableState($shopItem);
		$this->ensureBuyerIsNotSeller($shopItem, $buyer, $seller);
		$this->ensureBuyerHasNotAlreadyBoughtTheShopItem($shopItem, $buyer);

		dd([
			'precondition checks were successful',
			'shop_item' => $shopItem->getAttributes(),
			'seller' => $seller->getAttributes(),
			'buyer' => $buyer->getAttributes(),
		]);
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

	protected function ensureBuyerIsNotSeller(\ShopItem $shopItem, \User $buyer, \User $seller)
	{
		if ((int)$buyer->id === (int)$seller->id) {
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
