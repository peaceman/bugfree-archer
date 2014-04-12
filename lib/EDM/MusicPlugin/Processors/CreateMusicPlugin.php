<?php
namespace EDM\MusicPlugin\Processors;

use EDM\Common;
use EDM\MusicPlugin\ValidationRules;
use EDM\ProcessorInterface;
use EDM\User\UserInjection;
use Log;
use MusicPlugin;
use Review;
use Validator;

class CreateMusicPlugin implements ProcessorInterface
{
	use UserInjection;

	public function process(array $data = null)
	{
		$validationRules = (new ValidationRules\NewPluginFromUserInput())
			->getValidationRules();

		$inputData = array_only($data, array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			throw new Common\Exception\Validation($validator);
		}

		$plugin = new MusicPlugin($inputData);
		$plugin->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$plugin->save()) {
			Log::error(
				'failed to store a user supplied music plugin',
				['music_plugin' => $plugin->getAttributes()]
			);
			throw new \RuntimeException('unexpected system failure');
		}

		$plugin->review()->save(new Review());
		return $plugin;
	}
}
