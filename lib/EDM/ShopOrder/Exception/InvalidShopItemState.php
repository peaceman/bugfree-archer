<?php
namespace EDM\ShopOrder\Exception;

class InvalidShopItemState extends \LogicException
{
	/**
	 * @var \ShopItem
	 */
	public $shopItem;

	public function __construct(\ShopItem $shopItem)
	{
		$this->shopItem = $shopItem;
	}
} 
