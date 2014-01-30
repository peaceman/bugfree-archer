<?php
namespace EDM\ModelTraits;

use EDM\ModelTraits\Reviewable;

/**
 * Class Reviewable
 * @package EDM\ModelTraits
 *
 * @property int $review_id
 * @property \Review $review
 */
trait Reviewable
{
	public function review()
	{
		return $this->belongsTo('Review');
	}

	public function scopeAccepted($query)
	{
		return $query
			->whereHas('review', function ($q) {
				$q->where('result', '=', true);
			})
			->orWhere('review_id', '=', null);
	}
}
