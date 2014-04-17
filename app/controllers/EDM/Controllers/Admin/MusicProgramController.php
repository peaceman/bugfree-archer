<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class MusicProgramController extends AuthenticatedBaseController
{
	public function index()
	{
		$musicPrograms = \MusicProgram::query()
			->orderBy('created_at', 'desc')
			->paginate();

		return $this->response->view('admin.music-program.index', [
			'musicPrograms' => $musicPrograms,
			'amountOfMusicPrograms' => \MusicProgram::count(),
		]);
	}
}
