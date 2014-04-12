<?php
namespace EDM\MusicPluginBank\Processors;

use EDM\Common;
use EDM\MusicPluginBank\ValidationRules;
use EDM\ProcessorInterface;
use EDM\User\UserInjection;
use Log;
use MusicPluginBank;
use Review;
use Validator;

class CreateMusicPluginBank implements ProcessorInterface
{
	use UserInjection;

	public function process(array $data = null)
	{
		$validationRules = (new ValidationRules\NewPluginBankFromUserInput())
			->getValidationRules();

		$inputData = array_only($data, array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			throw new Common\Exception\Validation($validator);
		}

		$bank = new MusicPluginBank($inputData);
		$bank->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$bank->save()) {
			Log::error('failed to store a user supplied music bank', ['music_bank' => $bank->getAttributes()]);
			throw new \RuntimeException('unexpected system failure');
		}

		$bank->review()->save(new Review());
		return $bank;
	}
}
