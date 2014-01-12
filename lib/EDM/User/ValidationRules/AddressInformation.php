<?php
namespace EDM\User\ValidationRules;

use EDM\ValidationRulesInterface;

class AddressInformation implements ValidationRulesInterface
{
	/**
	 * @return array
	 */
	public function getValidationRules()
	{
		return [
			'locality' => ['required', 'max:255'],
			'postcode' => ['required', 'max:255'],
			'address_lines' => ['required',]
		];
	}
}
