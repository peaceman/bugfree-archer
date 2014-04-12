<?php
namespace EDM\ShopItemRevision;

class ValidatorBag
{
	/**
	 * @var ValidatorInterface[]
	 */
	public $preSave = [];

	/**
	 * @var ValidatorInterface[]
	 */
	public $postSave = [];
}
