<?php
namespace EDM\ModelTraits;

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
}
