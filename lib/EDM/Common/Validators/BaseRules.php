<?php
namespace EDM\Common\Validators;

class BaseRules extends \EDM\AbstractBaseValidator implements \EDM\ValidatorInterface
{
	public function validate($model)
	{
		$validationRules = $model::$validationRules;
		$validationData = array_only(
			$model->getAttributes(),
			array_keys($validationRules)
		);

		$validator = $this->validatorFactory->make($validationData, $validationRules);

		if ($validator->fails()) {
			throw new \EDM\Common\Exception\Validation($validator);
		}
	}
}
