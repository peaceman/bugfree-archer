<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;
use Review;

class ReviewController extends AuthenticatedBaseController
{
	public function index()
	{
		return $this->response->view('admin.review.index', [
			'reviewsInFinishedState' => Review::fetchPaginatedReviewsWithState(Review::STATE_FINISHED),
			'reviewsInInProgressState' => Review::fetchPaginatedReviewsWithState(Review::STATE_IN_PROGRESS),
			'reviewsInWaitingState' => Review::fetchPaginatedReviewsWithState(Review::STATE_WAITING, 5),
		]);
	}
}
