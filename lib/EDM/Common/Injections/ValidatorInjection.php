<?php
namespace EDM\Common\Injections;

use Illuminate\Validation\Validator;

trait ValidatorInjection
{
	/**
	 * @var Validator
	 */
	protected $validator;

	public function injectValidator(Validator $validator)
	{
		$this->validator = $validator;
	}
}
