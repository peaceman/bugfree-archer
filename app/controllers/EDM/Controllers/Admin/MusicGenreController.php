<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class MusicGenreController extends AuthenticatedBaseController
{
	public function index()
	{
		$musicGenres = \MusicGenre::query()
			->orderBy('created_at', 'desc')
			->paginate();

		return $this->response->view('admin.music-genre.index', [
			'musicGenres' => $musicGenres,
			'amountOfMusicGenres' => \MusicGenre::count(),
		]);
	}
}
