<?php
namespace EDM\ShopItemRevision\Processors;

use EDM\Common;
use EDM\ProcessorInterface;
use EDM\ShopItemRevision\ValidationRules;
use Illuminate\Support\Str;
use Review;
use ShopItemRevision;
use Validator;

class CreateShopItemRevision implements ProcessorInterface
{
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

		$data['product_revision']->shopItemRevision()->save($shopItemRevision);
		$shopItemRevision->review()->save(new Review());

		return $shopItemRevision;
	}

	protected function generateSlug(ShopItemRevision $shopItemRevision)
	{
		$slug = Str::limit(
			sprintf(
				'%s-%s',
				Str::quickRandom(3),
				Str::slug(object_get($shopItemRevision, 'title'))
			),
			32,
			''
		);

		return $slug;
	}
}
