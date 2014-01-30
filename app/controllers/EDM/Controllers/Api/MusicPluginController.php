<?php
namespace EDM\Controllers\Api;

use EDM\MusicPlugin\ValidationRules;
use MusicPlugin, Review, Validator, Log;

class MusicPluginController extends BaseController
{
	public function index()
	{
		$query = MusicPlugin::accepted()->asUser($this->user);

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

		$review = new Review();
		$review->reviewee_type = Review::REVIEWEE_MUSIC_PLUGIN;
		$review->save();

		$plugin = new MusicPlugin($inputData);
		$plugin->userTrackingSession()->associate($this->user->fetchLastTrackingSession());
		$plugin->review()->associate($review);

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

		return $this->response->json($plugin, 201);
	}
}