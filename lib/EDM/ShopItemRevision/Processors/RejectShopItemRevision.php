<?php
namespace EDM\ShopItemRevision\Processors;

class RejectShopItemRevision extends AbstractBaseProcessor
{
	public function process(array $data = null)
	{
		/** @var \ShopItemRevision $shopItemRevision */
		$shopItemRevision = $this->requireData($data, 'shop_item_revision');

		$this->sendNotificationMail($shopItemRevision);
	}

	protected function sendNotificationMail(\ShopItemRevision $shopItemRevision)
	{
		/** @var \ShopItem $shopItem */
		$shopItem = $shopItemRevision->shopItem;

		// todo send notification mail
	}

} 
