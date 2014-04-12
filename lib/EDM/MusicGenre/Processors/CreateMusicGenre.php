<?php
namespace EDM\MusicGenre\Processors;

use EDM\Common;
use EDM\MusicGenre\ValidationRules;
use EDM\ProcessorInterface;
use EDM\User\UserInjection;
use Log;
use MusicGenre;
use Review;
use Validator;

class CreateMusicGenre implements ProcessorInterface
{
	use UserInjection;

	public function process(array $data = null)
	{
		$validationRules = (new ValidationRules\NewGenreFromUserInput())
			->getValidationRules();

		$inputData = array_only($data, array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			throw new Common\Exception\Validation($validator);
		}

		$genre = new MusicGenre($inputData);
		$genre->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$genre->save()) {
			Log::error('failed to store a user supplied music genre', ['music_genre' => $genre->getAttibutes()]);
			throw new \RuntimeException('unexpected system failure');
		}

		$genre->review()->save(new Review());
		return $genre;
	}
}
