<?php
namespace EDM\Common\Injections;

use Illuminate\Validation\Factory;

trait ValidatorInjection
{
	/**
	 * @var Factory
	 */
	protected $validatorFactory;

	public function injectValidator(Factory $validator)
	{
		$this->validatorFactory = $validator;
	}
}
