<?php
namespace EDM\MusicProgram\Processors;

use EDM\Common;
use EDM\MusicProgram\ValidationRules;
use EDM\ProcessorInterface;
use EDM\User\UserInjection;
use Log;
use MusicProgram;
use Review;
use Validator;

class CreateMusicProgram implements ProcessorInterface
{
	use UserInjection;

	public function process(array $data = null)
	{
		$validationRules = (new ValidationRules\NewProgramFromUserInput())
			->getValidationRules();

		$inputData = array_only($data, array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			throw new Common\Exception\Validation($validator);
		}

		$program = new MusicProgram($inputData);
		$program->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$program->save()) {
			Log::error('failed to store a user supplied music program', ['music_program' => $program->getAttributes()]);
			throw new \RuntimeException('unexpected system failure');
		}

		$program->review()->save(new Review());
		return $program;
	}
}
