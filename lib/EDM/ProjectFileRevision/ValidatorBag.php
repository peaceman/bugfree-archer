<?php
namespace EDM\ProjectFileRevision;

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
