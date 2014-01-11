<?php
namespace EDM\User\Process\Exception\EmailConfirmation;

abstract class AbstractException extends \RuntimeException
{
	protected $confirmation;

	public function __construct(\UserEmailConfirmation $confirmation = null)
	{
		$this->confirmation = $confirmation;
	}

	public function getConfirmation()
	{
		return $this->confirmation;
	}
}
