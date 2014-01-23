<?php
namespace EDM\Controllers\Api;

use ShopCategory;

class ShopCategoryController extends BaseController
{
	public function index()
	{
		$categories = ShopCategory::query()->get();
		return $this->response->json($categories);
	}
}
