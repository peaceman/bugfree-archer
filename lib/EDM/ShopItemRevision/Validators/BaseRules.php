<?php
namespace EDM\ShopItemRevision\Validators;

use EDM\ShopItemRevision\ValidatorInterface;

class BaseRules implements ValidatorInterface
{
	/**
	 * @var \Illuminate\Validation\Factory
	 */
	protected $validatorFactory;

	public function __construct(\Illuminate\Validation\Factory $validatorFactory)
	{
		$this->validatorFactory = $validatorFactory;
	}

	/**
	 * @param \ShopItemRevision $shopItemRevision
	 * @throws \EDM\Common\Exception\Validation
	 */
	public function validate(\ShopItemRevision $shopItemRevision)
	{
		$validationRules = \ShopItemRevision::$validationRules;
		$validationData = array_only(
			$shopItemRevision->getAttributes(),
			array_keys($validationRules)
		);

		$validator = $this->validatorFactory->make($validationData, $validationRules);

		if ($validator->fails()) {
			throw new \EDM\Common\Exception\Validation($validator);
		}
	}

}
