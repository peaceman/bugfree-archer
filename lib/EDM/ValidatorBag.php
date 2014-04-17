<?php
namespace EDM;

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
