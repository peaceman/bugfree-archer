<?php
namespace EDM\Controllers\Api;

use EDM\MusicPlugin\ValidationRules;
use MusicPlugin, Review, Validator, Log;

class MusicPluginController extends BaseController
{
	public function index()
	{
		$query = MusicPlugin::accepted()->asUser($this->user)->with('banks');

		if ($this->request->has('q')) {
			$query->where('name', 'like', $this->request->get('q'));
		}

		$musicPlugins = $query->get();
		return $this->response->json($musicPlugins);
	}

	public function store()
	{
		$validationRules = (new ValidationRules\NewPluginFromUserInput())
			->getValidationRules();
		$inputData = $this->request->only(array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			return $this->response->json(
				['errors' => $validator->errors()->toArray()],
				400
			);
		}

		$plugin = new MusicPlugin($inputData);
		$plugin->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$plugin->save()) {
			Log::error(
				'failed to store a user supplied music plugin',
				['music_plugin' => $plugin->getAttributes()]
			);
			return $this->response->json(
				['errors' => ['unexpected system failure']],
				500
			);
		}

		$plugin->review()->save(new Review());
		return $this->response->json($plugin, 201);
	}
}