<?php
namespace EDM\MusicPluginBank\ValidationRules;

use EDM\ValidationRulesInterface;
use MusicPluginBank;

class NewPluginBankFromUserInput implements ValidationRulesInterface
{
	protected static $relevantValidationRulesFromModel = [
		'name', 'music_plugin_id',
	];

	public function getValidationRules()
	{
		return array_only(
			MusicPluginBank::$validationRules,
			static::$relevantValidationRulesFromModel
		);
	}
}