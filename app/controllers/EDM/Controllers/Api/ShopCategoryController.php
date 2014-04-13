<?php
namespace EDM\Controllers\Api;

use EDM\Controllers\AuthenticatedBaseController;
use ShopCategory;

class ShopCategoryController extends AuthenticatedBaseController
{
	public function index()
	{
		$categories = ShopCategory::query()->get();
		return $this->response->json($categories);
	}
}
