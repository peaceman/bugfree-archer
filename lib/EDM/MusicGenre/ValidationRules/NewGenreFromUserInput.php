<?php
namespace EDM\MusicGenre\ValidationRules;

use EDM\ValidationRulesInterface;
use MusicGenre as Model;

class NewGenreFromUserInput implements ValidationRulesInterface
{
	public function getValidationRules()
	{
		return [
			'name' => Model::$validationRules['name'],
		];
	}
}