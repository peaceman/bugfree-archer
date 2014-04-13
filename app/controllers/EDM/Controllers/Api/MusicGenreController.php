<?php
namespace EDM\Controllers\Api;

use EDM\Controllers\AuthenticatedBaseController;
use EDM\MusicGenre\ValidationRules;
use Log;
use MusicGenre;
use Review;
use Validator;

class MusicGenreController extends AuthenticatedBaseController
{
	public function index()
	{
		$query = MusicGenre::accepted()->asUser($this->user);

		if ($this->request->has('q')) {
			$query->where('name', 'like', $this->request->get('q'));
		}

		$musicGenres = $query->get();
		return $this->response->json($musicGenres);
	}

	public function store()
	{
		$validationRules = (new ValidationRules\NewGenreFromUserInput())
			->getValidationRules();
		$inputData = $this->request->only(array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			return $this->response->json(['errors' => $validator->errors()->toArray()], 400);
		}

		$genre = new MusicGenre($inputData);
		$genre->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$genre->save()) {
			Log::error('failed to store a user supplied music genre', ['music_genre' => $genre->getAttibutes()]);
			return $this->response->json(['errors' => ['unexpected system failure']], 500);
		}

		$genre->review()->save(new Review());
		return $this->response->json($genre, 201);
	}
}
