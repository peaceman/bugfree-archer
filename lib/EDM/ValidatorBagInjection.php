<?php
namespace EDM;

trait ValidatorBagInjection
{
	/**
	 * @var ValidatorBag
	 */
	protected $validatorBag;

	public function injectValidatorBag(ValidatorBag $validatorBag)
	{
		$this->validatorBag = $validatorBag;
	}
}
