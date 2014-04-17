<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class MusicPluginController extends AuthenticatedBaseController
{
	public function index()
	{
		$musicPlugins = \MusicPlugin::query()
			->orderBy('created_at', 'desc')
			->paginate();

		return $this->response->view('admin.music-plugin.index', [
			'musicPlugins' => $musicPlugins,
			'amountOfMusicPlugins' => \MusicPlugin::count(),
		]);
	}
}
