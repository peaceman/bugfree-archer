<?php
namespace EDM\Review\Processors;

class StartReview extends AbstractBaseProcessor
{
	public function process(array $data = null)
	{
		/** @var \Review $review */
		$review = $this->requireData($data, 'review');

		$this->checkState($review);

		$review->state = \Review::STATE_IN_PROGRESS;
		$review->reviewer()->associate($this->user);

		$this->executeValidatorsOnReview($this->validatorBag->preSave, $review);
		$review->save();
		$this->executeValidatorsOnReview($this->validatorBag->postSave, $review);
	}

	/**
	 * @param \Review $review
	 * @throws \EDM\Review\Exception\InvalidState
	 */
	protected function checkState(\Review $review)
	{
		if ($review->state !== \Review::STATE_WAITING) {
			throw new \EDM\Review\Exception\InvalidState($review);
		}
	}
}
