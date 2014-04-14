<?php
namespace EDM\Controllers\Admin;

class UserController extends \EDM\Controllers\AuthenticatedBaseController
{
	public function index()
	{
		$users = \User::query()
			->orderBy('created_at', 'desc')
			->paginate();

		$latestActiveUsers = \User::where('state', \User::STATE_ACTIVE)
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();

		$latestUnconfirmedUsers = \User::where('state', \User::STATE_UNCONFIRMED_EMAIL)
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();

		return $this->response->view('admin.user.index', [
			'users' => $users,
			'latestActiveUsers' => $latestActiveUsers,
			'latestUnconfirmedUsers' => $latestUnconfirmedUsers,
			'amountOfActiveUsers' => \User::where('state', \User::STATE_ACTIVE)->count(),
		]);
	}
}
