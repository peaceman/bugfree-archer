<?php
namespace EDM\User\ValidationRules;

use EDM\ValidationRulesInterface;

class BasicInformation implements ValidationRulesInterface
{
	public function getValidationRules()
	{
		return [
			'website' => ['url'],
			'about' => ['max:5000'],
			'avatar' => ['image'],
		];
	}
}
