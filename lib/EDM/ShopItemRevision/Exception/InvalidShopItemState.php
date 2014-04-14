<?php
namespace EDM\ShopItemRevision\Exception;

class InvalidShopItemState extends \LogicException
{
	/**
	 * @var \ShopItemRevision
	 */
	public $shopItemRevision;

	public function __construct(\ShopItemRevision $shopItemRevision)
	{
		$this->shopItemRevision = $shopItemRevision;
	}
}
