<?php
namespace EDM\ShopOrder\Exception;

class UserIsNotBuyer extends \LogicException
{
	/**
	 * @var \ShopOrder
	 */
	public $shopOrder;

	/**
	 * @var \User
	 */
	public $user;

	public function __construct(\ShopOrder $shopOrder, \User $user)
	{
		$this->shopOrder = $shopOrder;
		$this->user = $user;
	}
}
