<?php
namespace EDM\Controllers\User;

use View;

class OrderController extends UserBaseController
{
	public function index()
	{
		$ordersQuery = \ShopOrder::onlyFromUser($this->user)
			->orderBy('created_at', 'desc');
		$orders = $ordersQuery->paginate();

		return $this->response->view('user.orders.index', compact('orders'));
	}

	/**
	 * displays the purchase confirmation page
	 */
	public function create()
	{
		$shopItemSlug = $this->request->get('shop_item_slug');
		$shopItem = \ShopItem::fetchActiveShopItemWithSlug($shopItemSlug);
		$shopItemRevision = $shopItem->activeRevision;
		$productRevision = $shopItemRevision->productRevision;
		$seller = $shopItem->owner;

		return $this->response->view(
			'user.orders.create',
			compact('shopItem', 'shopItemRevision', 'productRevision', 'seller')
		);
	}

	public function postDownload($username, $orderId)
	{
		$order = \ShopOrder::onlyFromUser($this->user)
			->findOrFail($orderId);

		try {
			/** @var \EDM\ShopOrder\Processors\GenerateDownloadUrl $generateDownloadUrlProcessor */
			$generateDownloadUrlProcessor = \App::make(\EDM\ShopOrder\Processors\GenerateDownloadUrl::class);
			$downloadUrl = $generateDownloadUrlProcessor->process([
				'order' => $order,
			]);
		} catch (\EDM\ShopOrder\Exception\InvalidPaymentState $e) {
			\Notification::error(trans('user.orders.notifications.download_url_generation_failed'));
			\Log::info('failed to generate the order download url, because of an invalid payment state', [
				'shop_order' => $e->shopOrder->getAttributes(),
				'username' => $username,
			]);
			return $this->redirector->back();
		} catch (\EDM\ShopOrder\Exception\UserIsNotBuyer $e) {
			\Log::error('failed to generate the order download url, because the user is not the buyer', [
				'shop_order' => $e->shopOrder->getAttributes(),
				'user' => $e->user->getAttributes(),
			]);

			return $this->response->make('Forbidden', 403);
		}

		return $this->redirector->away($downloadUrl);
	}
}
