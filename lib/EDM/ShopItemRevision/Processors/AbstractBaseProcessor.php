<?php
namespace EDM\ShopItemRevision\Processors;

use EDM\ProcessorInterface;
use EDM\ShopItemRevision\ValidatorBag;
use Illuminate\Support\Str;
use ShopItemRevision;

abstract class AbstractBaseProcessor extends \EDM\AbstractBaseProcessor implements ProcessorInterface
{
	/**
	 * @var ValidatorBag
	 */
	protected $validatorBag;

	public function __construct(ValidatorBag $validatorBag)
	{
		$this->validatorBag = $validatorBag;
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

	/**
	 * @param \EDM\ShopItemRevision\ValidatorInterface[] $validators
	 * @param \ShopItemRevision $shopItemRevision
	 */
	protected function executeValidatorsOnShopItemRevision(array $validators, \ShopItemRevision $shopItemRevision)
	{
		foreach ($validators as $validator) {
			$validator->validate($shopItemRevision);
		}
	}
}
