<?php
namespace EDM\Controllers\Api;

use EDM\MusicProgram\ValidationRules;
use MusicProgram, Review, Validator, Log;

class MusicProgramController extends BaseController
{
	public function index()
	{
		$query = MusicProgram::accepted()->asUser($this->user);

		if ($this->request->has('q')) {
			$query->where('name', 'like', $this->request->get('q'));
		}

		$musicPrograms = $query->get();
		return $this->response->json($musicPrograms);
	}

	public function store()
	{
		$validationRules = (new ValidationRules\NewProgramFromUserInput())
			->getValidationRules();
		$inputData = $this->request->only(array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			return $this->response->json(['errors' => $validator->errors()->toArray()], 400);
		}

		$review = new Review();
		$review->reviewee_type = Review::REVIEWEE_MUSIC_PROGRAM;
		$review->save();

		$program = new MusicProgram($inputData);
		$program->userTrackingSession()->associate($this->user->fetchLastTrackingSession());
		$program->review()->associate($review);

		if (!$program->save()) {
			Log::error('failed to store a user supplied music program', ['music_program' => $program->getAttributes()]);
			return $this->response->json(['errors' => ['unexpected system failure']], 500);
		}

		return $this->response->json($program, 201);
	}
}