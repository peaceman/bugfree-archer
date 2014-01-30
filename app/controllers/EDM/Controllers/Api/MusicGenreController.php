<?php
namespace EDM\Controllers\Api;

use EDM\MusicGenre\ValidationRules;
use MusicGenre, Review, Validator, Log;

class MusicGenreController extends BaseController
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

		$review = new Review();
		$review->reviewee_type = Review::REVIEWEE_MUSIC_GENRE;
		$review->save();

		$genre = new MusicGenre($inputData);
		$genre->userTrackingSession()->associate($this->user->fetchLastTrackingSession());
		$genre->review()->associate($review);

		if (!$genre->save()) {
			Log::error('failed to store a user supplied music genre', ['music_genre' => $genre->getAttibutes()]);
			return $this->response->json(['errors' => ['unexpected system failure']], 500);
		}

		return $this->response->json($genre, 201);
	}
}