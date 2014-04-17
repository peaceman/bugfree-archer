<?php
namespace EDM;

abstract class AbstractBaseValidator implements ValidatorInterface
{
	/**
	 * @var \Illuminate\Validation\Factory
	 */
	protected $validatorFactory;

	public function __construct(\Illuminate\Validation\Factory $validatorFactory)
	{
		$this->validatorFactory = $validatorFactory;
	}
}
