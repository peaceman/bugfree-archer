<?php
namespace EDM\MusicProgram\ValidationRules;

use EDM\ValidationRulesInterface;
use MusicProgram;

class NewProgramFromUserInput implements ValidationRulesInterface
{
	public function getValidationRules()
	{
		return [
			'name' => MusicProgram::$validationRules['name'],
		];
	}
}