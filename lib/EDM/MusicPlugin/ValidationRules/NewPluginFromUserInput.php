<?php
namespace EDM\MusicPlugin\ValidationRules;

use EDM\ValidationRulesInterface;
use MusicPlugin;

class NewPluginFromUserInput implements ValidationRulesInterface
{
	public function getValidationRules()
	{
		return ['name' => MusicPlugin::$validationRules['name']];
	}
}