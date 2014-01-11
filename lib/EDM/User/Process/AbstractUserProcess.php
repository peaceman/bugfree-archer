<?php
namespace EDM\User\Process;

use EDM\ProcessInterface;
use User;

abstract class AbstractUserProcess implements ProcessInterface
{
	/**
	 * @var \User
	 */
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}
}