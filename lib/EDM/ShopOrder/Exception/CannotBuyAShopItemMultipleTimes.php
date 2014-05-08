<?php
namespace EDM\ShopOrder\Exception;

class CannotBuyAShopItemMultipleTimes extends \LogicException
{
	/**
	 * @var \ShopItem
	 */
	public $shopItem;

	/**
	 * @var \User
	 */
	public $buyer;

	public function __construct(\ShopItem $shopItem, \User $buyer)
	{
		$this->buyer = $buyer;
		$this->shopItem = $shopItem;
	}

} 
