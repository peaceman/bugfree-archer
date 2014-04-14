<?php
namespace EDM\Review\Exception;

class InvalidState extends \LogicException
{
	/**
	 * @var \Review $review
	 */
	public $review;

	public function __construct(\Review $review)
	{
		$this->review = $review;
	}
}
