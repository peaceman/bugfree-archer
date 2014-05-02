<?php
namespace EDM\ShopOrder\Exception;

class InvalidPaymentState extends \LogicException
{
	/**
	 * @var \ShopOrder
	 */
	public $shopOrder;

	/**
	 * @param \ShopOrder $shopOrder
	 */
	public function __construct(\ShopOrder $shopOrder)
	{
		$this->shopOrder = $shopOrder;
	}
}
