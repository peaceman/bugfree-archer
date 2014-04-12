<?php
namespace EDM\Common\Exception;

use Illuminate\Validation\Validator;

class Validation extends \RuntimeException
{
	public $validator;

	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	public function getErrors()
	{
		return $this->validator->errors()->toArray();
	}
}
