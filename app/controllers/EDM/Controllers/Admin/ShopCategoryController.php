<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class ShopCategoryController extends AuthenticatedBaseController
{
	public function index()
	{
		$shopCategories = \ShopCategory::all();
		return $this->response->view('admin.shop-category.index', [
			'shopCategories' => $shopCategories,
		]);
	}
}
