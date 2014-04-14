<?php
namespace EDM\ShopItemRevision\Processors;

use EDM\Common;
use EDM\ShopItemRevision\ValidationRules;
use EDM\User\UserInjection;
use Review;
use ShopItemRevision;
use Validator;

class CreateShopItemRevision extends AbstractBaseProcessor
{
	use UserInjection;

	public function process(array $data = null)
	{
		$validationRules = (new ValidationRules\NewShopItemRevisionFromUserInput())
			->getValidationRules();

		$validationData = array_only($data, array_keys($validationRules));
		$validator = Validator::make($validationData, $validationRules);

		if ($validator->fails()) {
			throw new Common\Exception\Validation($validator);
		}

		$shopItemRevision = new ShopItemRevision($validationData);
		$shopItemRevision->slug = $this->generateSlug($shopItemRevision);

		$shopItemRevision->shopCategory()->associate($data['shop_category']);
		$shopItemRevision->shopItem()->associate($data['shop_item']);
		$shopItemRevision->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		$data['product_revision']->shopItemRevision()->save($shopItemRevision);
		$shopItemRevision->review()->save(new Review());

		return $shopItemRevision;
	}

}
