<?php
class ShopItemController extends BaseController
{
	public function getIndexByCategory($categorySlugs)
	{
		$categorySlugs = explode('/', $categorySlugs);
		$categorySlug = array_pop($categorySlugs);

		/** @var ShopCategory $category */
		$category = ShopCategory::where('slug', $categorySlug)->firstOrFail();
		$shopItems = $category->getAcceptedShopItems()->paginate();

		return $this->response->view('shop-item.index', [
			'shopCategory' => $category,
			'shopItems' => $shopItems,
		]);
	}

	public function getShow($itemSlug)
	{
		$shopItem = ShopItem::withState(ShopItem::STATE_ACTIVE)
			->whereHas('activeRevision', function ($query) use ($itemSlug) {
				$query->where('slug', '=', $itemSlug);
			})
			->firstOrFail();

		return $this->response->view('shop-item.show', [
			'shopItem' => $shopItem,
			'shopItemRevision' => $shopItem->activeRevision,
			'productRevision' => $shopItem->activeRevision->productRevision,
			'seller' => $shopItem->owner,
			'comments' => $shopItem->comments()->orderBy('created_at', 'asc')->get(),
		]);
	}
}
