<?php
class ShopItemController extends BaseController
{
	use EDM\Common\Injections\AuthManagerInjection;
	use EDM\Common\Injections\SessionManagerInjection;

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
		$shopItem = ShopItem::fetchActiveShopItemWithSlug($itemSlug);

		return $this->response->view('shop-item.show', [
			'shopItem' => $shopItem,
			'shopItemRevision' => $shopItem->activeRevision,
			'productRevision' => $shopItem->activeRevision->productRevision,
			'seller' => $shopItem->owner,
			'comments' => $shopItem->comments()->orderBy('created_at', 'asc')->get(),
			'buyNowLink' => $this->generateBuyNowLinkForShopItemRevision($shopItem->activeRevision),
		]);
	}

	protected function generateBuyNowLinkForShopItemRevision(ShopItemRevision $shopItemRevision)
	{
		if ($this->auth->check()) {
			/** @var $user User */
			$user = $this->auth->user();
			return $this->generateBuyNowLinkForShopItemRevisionAndUsername($shopItemRevision, $user->username);
		} else {
			$this->session->set(
				'url.intended',
				$this->generateBuyNowLinkForShopItemRevisionAndUsername($shopItemRevision, '##USERNAME##')
			);

			return route('auth.log-in');
		}
	}

	protected function generateBuyNowLinkForShopItemRevisionAndUsername(ShopItemRevision $shopItemRevision, $username)
	{
		$link = route('users.orders.create', [$username, 'shop_item_slug' => $shopItemRevision->slug]);
		return $link;
	}
}
