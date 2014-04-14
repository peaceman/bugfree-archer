<?php
namespace EDM\Review\Validators;

class BaseRules implements \EDM\Review\ValidatorInterface
{
	use \EDM\Common\Injections\ValidatorInjection;

	public function validate(\Review $review)
	{
		$validationRules = \Review::$validationRules;
		$validationData = array_only(
			$review->getAttributes(),
			array_keys($validationRules)
		);

		$validator = $this->validatorFactory->make($validationData, $validationRules);

		if ($validator->fails()) {
			throw new \EDM\Common\Exception\Validation($validator);
		}
	}
}
