<?php
namespace EDM\Review\Processors;

abstract class AbstractBaseProcessor implements \EDM\ProcessorInterface
{
	use \EDM\User\UserInjection;

	/**
	 * @var \EDM\Review\ValidatorBag
	 */
	protected $validatorBag;

	/**
	 * @param \EDM\Review\ValidatorBag $validatorBag
	 */
	public function __construct(\EDM\Review\ValidatorBag $validatorBag)
	{
		$this->validatorBag = $validatorBag;
	}

	protected function requireData(array $source, $key, $default = null)
	{
		$result = array_get($source, $key, $default);

		if (is_null($result)) {
			throw new \EDM\Common\Exception\MissingParameter($source, $key);
		}

		return $result;
	}

	/**
	 * @param \EDM\Review\ValidatorInterface[] $validators
	 * @param \Review $review
	 */
	protected function executeValidatorsOnReview(array $validators, \Review $review)
	{
		foreach ($validators as $validator) {
			$validator->validate($review);
		}
	}
}
