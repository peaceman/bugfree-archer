<?php
namespace EDM\Review\Processors;

abstract class AbstractBaseProcessor extends \EDM\AbstractBaseProcessor implements \EDM\ProcessorInterface
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
