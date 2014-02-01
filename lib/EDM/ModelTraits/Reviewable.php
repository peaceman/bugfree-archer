<?php
namespace EDM\ModelTraits;

/**
 * Class Reviewable
 * @package EDM\ModelTraits
 * 
 * @property \Review $review
 */
trait Reviewable
{
	public function review()
	{
		return $this->morphOne('Review', 'reviewee');
	}

	public function scopeAccepted($query)
	{
		return $query
			->whereHas('review', function ($q) {
				$q->where('state', '=', \Review::STATE_FINISHED)
					->where('result', '=', true);
			});
	}
}
