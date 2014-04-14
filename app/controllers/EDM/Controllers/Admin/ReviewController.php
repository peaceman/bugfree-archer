<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;
use Review;

class ReviewController extends AuthenticatedBaseController
{
	public function index()
	{
		return $this->response->view('admin.review.index', [
			'reviewsInFinishedState' => Review::fetchPaginatedReviewsWithState(Review::STATE_FINISHED, 'updated_at'),
			'reviewsInInProgressState' => Review::fetchPaginatedReviewsWithState(Review::STATE_IN_PROGRESS),
			'reviewsInWaitingState' => Review::fetchPaginatedReviewsWithState(Review::STATE_WAITING, null, 5),
		]);
	}

	public function show($reviewId)
	{
		$review = Review::findOrFail($reviewId);

		return $this->response->view('admin.review.show', [
			'review' => $review,
		]);
	}

	public function update($reviewId)
	{
		$review = Review::findOrFail($reviewId);

		try {
			/** @var \EDM\Review\Processors\FinishReview $finishReviewProcessor */
			$finishReviewProcessor = \App::make(\EDM\Review\Processors\FinishReview::class);
			$finishReviewProcessor->process([
				'review' => $review,
				'review_result' => $this->request->has('accept') ? 1 : 0,
				'input_data' => $this->request->all(),
			]);

			\Notification::success(trans('admin.review.notifications.finished'));
			return $this->redirector->route('admin.reviews.index');
		} catch (\EDM\Common\Exception\Validation $e) {
			\Notification::error(trans('common.notifications.update.failed'));
			return $this->redirector->route('admin.reviews.show', ['reviewId' => $review->id])
				->withErrors($e->validator);
		}
	}

	public function postStart($reviewId)
	{
		$review = Review::findOrFail($reviewId);

		/** @var \EDM\Review\Processors\StartReview $startReviewProcessor */
		$startReviewProcessor = \App::make(\EDM\Review\Processors\StartReview::class);
		$startReviewProcessor->process([
			'review' => $review
		]);

		\Notification::info(trans('admin.review.notifications.started'));
		return $this->redirector->action('admin.reviews.show', [
			'reviewId' => $review->id,
		]);
	}
}
