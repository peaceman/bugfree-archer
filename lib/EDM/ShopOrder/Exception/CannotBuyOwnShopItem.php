<?php
namespace EDM\ShopOrder\Exception;

class CannotBuyOwnShopItem extends \LogicException
{
	/**
	 * @var \ShopItem
	 */
	public $shopItem;

	/**
	 * @var \User
	 */
	public $buyer;

	/**
	 * @var \User
	 */
	public $seller;

	public function __construct(\ShopItem $shopItem, \User $buyer, \User $seller)
	{
		$this->shopItem = $shopItem;
		$this->buyer = $buyer;
		$this->seller = $seller;
	}
}
