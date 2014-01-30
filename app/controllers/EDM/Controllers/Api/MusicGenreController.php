<?php
namespace EDM\Controllers\Api;

use MusicGenre;

class MusicGenreController extends BaseController
{
	public function index()
	{
		$musicGenres = MusicGenre::accepted()->asUser($this->user)->get();
		return $this->response->json($musicGenres);
	}
}