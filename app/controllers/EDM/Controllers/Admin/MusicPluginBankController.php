<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class MusicPluginBankController extends AuthenticatedBaseController
{
	public function index()
	{
		$musicPluginbanks = \MusicPluginBank::query()
			->orderBy('created_at', 'desc')
			->paginate();

		return $this->response->view('admin.music-plugin-bank.index', [
			'musicPluginBanks' => $musicPluginbanks,
			'amountOfMusicPluginBanks' => \MusicPluginBank::count(),
		]);
	}
}
