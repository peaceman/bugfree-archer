<?php
namespace EDM\Controllers\User;

class SaleController extends UserBaseController
{
	public function index()
	{
		$salesQuery = $this->user->generateGetSalesQuery()
			->orderBy('created_at', 'desc');
		$sales = $salesQuery->paginate();

		return $this->response->view('user.sales.index', compact('sales'));
	}
}
