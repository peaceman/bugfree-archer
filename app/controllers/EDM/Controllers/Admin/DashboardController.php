<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class DashboardController extends AuthenticatedBaseController
{
	public function show()
	{
		return $this->response->view('admin.dashboard.home');
	}
}
