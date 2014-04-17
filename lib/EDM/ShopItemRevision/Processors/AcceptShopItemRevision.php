<?php
namespace EDM\ShopItemRevision\Processors;

class AcceptShopItemRevision extends AbstractBaseProcessor
{
	use \EDM\Common\Injections\StorageDirectorInjection;

	public function process(array $data = null)
	{
		/** @var \ShopItemRevision $shopItemRevision */
		$shopItemRevision = $this->requireData($data, 'shop_item_revision');
		/** @var \ShopItem $shopItem */
		$shopItem = $shopItemRevision->shopItem;

		switch ($shopItem->state) {
			case \ShopItem::STATE_ACTIVE:
				$this->processRevisionUpdate($shopItemRevision);
				break;
			case \ShopItem::STATE_INACTIVE:
				$this->processFirstActivation($shopItemRevision);
				break;
			case \ShopItem::STATE_REPORTED:
			case \ShopItem::STATE_TEMPORARILY_BLOCKED:
				$this->processReactivation($shopItemRevision);
				break;
			default:
				throw new \EDM\ShopItemRevision\Exception\InvalidShopItemState($shopItemRevision);
		}

		$shopItem->activeRevision()->associate($shopItemRevision);
		$shopItem->save();
		// todo send notification mail
	}

	protected function publishFilesOfProductRevision($productRevision)
	{
		// todo create interface for product revisions
		$files = $productRevision->getFiles();
		foreach ($files as $fileInfo) {
			$this->storageDirector->queueDistributionOfResourceFile($fileInfo['file']);
		}
	}

	/**
	 * @param \ShopItemRevision $shopItemRevision
	 */
	protected function processRevisionUpdate(\ShopItemRevision $shopItemRevision)
	{
		$shopItemRevision->shopItem->touch();
		$this->publishFilesOfProductRevision($shopItemRevision->productRevision);
	}

	/**
	 * @param \ShopItemRevision $shopItemRevision
	 */
	protected function processFirstActivation(\ShopItemRevision $shopItemRevision)
	{
		$shopItem = $shopItemRevision->shopItem;
		$shopItem->state = \ShopItem::STATE_ACTIVE;
		$shopItem->save();

		$this->publishFilesOfProductRevision($shopItemRevision->productRevision);
	}

	/**
	 * @param \ShopItemRevision $shopItemRevision
	 */
	protected function processReactivation(\ShopItemRevision $shopItemRevision)
	{
		$shopItem = $shopItemRevision->shopItem;
		$shopItem->state = \ShopItem::STATE_ACTIVE;
		$shopItem->save();

		$this->publishFilesOfProductRevision($shopItemRevision->productRevision);
	}

} 
