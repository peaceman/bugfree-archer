<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class ResourceFileController extends AuthenticatedBaseController
{
	public function index()
	{
		$resourceFiles = \ResourceFile::query()
			->orderBy('created_at', 'desc')
			->paginate();

		return $this->response->view('admin.resource-file.index', [
			'resourceFiles' => $resourceFiles,
			'amountOfResourceFiles' => \ResourceFile::count(),
		]);
	}
}
