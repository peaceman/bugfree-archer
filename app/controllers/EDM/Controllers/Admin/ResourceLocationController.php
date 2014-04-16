<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class ResourceLocationController extends AuthenticatedBaseController
{
	public function index()
	{
		$resourceLocations = \ResourceLocation::all();
		return $this->response->view('admin.resource-location.index', [
			'resourceLocations' => $resourceLocations,
		]);
	}
}
