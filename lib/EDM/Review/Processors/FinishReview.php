<?php
namespace EDM\Review\Processors;

class FinishReview extends AbstractBaseProcessor
{
	public function process(array $data = null)
	{
		/** @var \Review $review */
		$review = $this->requireData($data, 'review');
		$inputData = $this->requireData($data, 'input_data');
		$reviewResult = $this->requireData($data, 'review_result');

		$this->checkState($review);

		$review->result = $reviewResult;
		$review->result_reasoning = array_get($inputData, 'result_reasoning');
		$review->state = \Review::STATE_FINISHED;

		$this->assignReviewer($review);

		$this->executeValidatorsOnReview($this->validatorBag->preSave, $review);
		$review->save();
		$this->executeValidatorsOnReview($this->validatorBag->postSave, $review);

		$review->reviewee->finishedReviewWithResult((bool)$review->result);
	}

	/**
	 * @param \Review $review
	 */
	protected function assignReviewer(\Review $review)
	{
		if (!$review->reviewer) {
			\Log::debug(
				'detected review without reviewer',
				['review_attributes' => $review->getAttributes()]
			);
		} elseif ($review->reviewer->id !== $this->user->id) {
			\Log::debug(
				'a user which has not started the review process finishes it',
				['review_attributes' => $review->getAttributes(), 'user_attributes' => $this->user->getAttributes()]
			);
		}
		$review->reviewer()->associate($this->user);
	}

	/**
	 * @param \Review $review
	 * @throws \EDM\Review\Exception\InvalidState
	 */
	protected function checkState(\Review $review)
	{
		if ($review->state !== \Review::STATE_IN_PROGRESS) {
			throw new \EDM\Review\Exception\InvalidState($review);
		}
	}
}
