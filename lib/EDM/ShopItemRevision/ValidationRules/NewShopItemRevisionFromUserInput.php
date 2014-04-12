<?php
namespace EDM\ShopItemRevision\ValidationRules;

use EDM\ValidationRulesInterface;
use ShopItemRevision;

class NewShopItemRevisionFromUserInput implements ValidationRulesInterface
{
	/**
	 * @return array
	 */
	public function getValidationRules()
	{
		return array_only(ShopItemRevision::$validationRules, ['price', 'title']);
	}
}
