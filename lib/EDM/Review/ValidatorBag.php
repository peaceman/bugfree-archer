<?php
namespace EDM\Review;

class ValidatorBag
{
	/**
	 * @var ValidatorInterface[]
	 */
	public $preSave = [];

	/**
	 * @var ValidatorInterface[]
	 */
	public $postSave = [];
}
