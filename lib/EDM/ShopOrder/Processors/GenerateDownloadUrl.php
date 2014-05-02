<?php
namespace EDM\ShopOrder\Processors;

use EDM\AbstractBaseProcessor;
use EDM\Common\Injections\StorageDirectorInjection;
use EDM\User\UserInjection;

class GenerateDownloadUrl extends AbstractBaseProcessor
{
	use UserInjection;
	use StorageDirectorInjection;

	/**
	 * @param array $data
	 * @return string
	 *
	 * @throws \EDM\ShopOrder\Exception\InvalidPaymentState
	 * @throws \EDM\ShopOrder\Exception\UserIsNotBuyer
	 */
	public function process(array $data = null)
	{
		/** @var \ShopOrder $order */
		$order = $this->requireData($data, 'order');

		$this->checkDownloadability($order);
		$this->checkUserIsBuyer($order);

		$downloadUrl = $order
			->shopItemRevision
			->productRevision
			->archiveFile
			->getUrl();

		return $downloadUrl;
	}

	/**
	 * @param \ShopOrder $order
	 *
	 * @throws \EDM\ShopOrder\Exception\InvalidPaymentState
	 */
	protected function checkDownloadability(\ShopOrder $order)
	{
		if (!$order->isProductDownloadable()) {
			throw new \EDM\ShopOrder\Exception\InvalidPaymentState($order);
		}
	}

	/**
	 * @param \ShopOrder $order
	 *
	 * @throws \EDM\ShopOrder\Exception\UserIsNotBuyer
	 */
	protected function checkUserIsBuyer(\ShopOrder $order)
	{
		if ($order->buyer->id !== $this->user->id) {
			throw new \EDM\ShopOrder\Exception\UserIsNotBuyer($order, $this->user);
		}
	}
}
