<?php
namespace EDM\Review;

interface ValidatorInterface
{
	public function validate(\Review $review);
}
