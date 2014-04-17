<?php
class ShopItemController extends BaseController
{
	public function getIndexByCategory($categorySlugs)
	{
		$categorySlugs = explode('/', $categorySlugs);
		$categorySlug = array_pop($categorySlugs);

		/** @var ShopCategory $category */
		$category = ShopCategory::where('slug', $categorySlug)->firstOrFail();
		$shopItemRevisions = $category->getAcceptedShopItemRevisions()->paginate();

		return $this->response->view('shop-item.index', [
			'shopItemRevisions' => $shopItemRevisions,
		]);
	}
}
