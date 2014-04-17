<?php
namespace EDM;

abstract class AbstractBaseProcessor implements ProcessorInterface
{
	/**
	 * @var ValidatorBag
	 */
	protected $validatorBag;

	/**
	 * @param ValidatorBag
	 */
	public function __construct(ValidatorBag $validatorBag)
	{
		$this->validatorBag = $validatorBag;
	}

	/**
	 * @param \EDM\ValidatorInterface[] $validators
	 * @param object $model
	 */
	protected function executeValidatorsOnProjectFileRevision(array $validators, $model)
	{
		foreach ($validators as $validator) {
			$validator->validate($model);
		}
	}

	/**
	 * @param array $source
	 * @param $key
	 * @throws Common\Exception\MissingParameter
	 * @internal param null $default
	 * @return mixed
	 */
	protected function requireData(array $source, $key)
	{
		$result = array_get($source, $key, null);

		if (is_null($result)) {
			throw new \EDM\Common\Exception\MissingParameter($source, $key);
		}

		return $result;
	}
}
