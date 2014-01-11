<?php
namespace EDM\User\ValidationRules;

use EDM\ValidationRulesInterface;
use User;

class AccountInformation implements ValidationRulesInterface
{
	/**
	 * @var User
	 */
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function getValidationRules()
	{
		return array_merge($this->getStaticRules(), $this->getDynamicRules());
	}

	protected function getStaticRules()
	{
		return [
			'real_name' => User::$validationRules['real_name'],
		];
	}

	protected function getDynamicRules()
	{
		return [
			'email' => $this->user->getEmailValidationRule(),
		];
	}
}