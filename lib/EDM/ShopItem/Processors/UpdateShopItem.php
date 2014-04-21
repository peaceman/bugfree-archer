<?php
namespace EDM\ShopItem\Processors;

use App;
use EDM\ProductRevision;
use EDM\ShopItemRevision\Processors\UpdateShopItemRevision as UpdateShopItemRevisionProcessor;
use EDM\User\UserInjection;
use Illuminate\Support\Str;
use ShopCategory;
use ShopItemRevision;

class UpdateShopItem extends CreateShopItem
{
	public function __construct(ProductRevision\CreateProcessorManager $productRevisionCreateProcessorManager, ProductRevision\UpdateProcessorManager $productRevisionUpdateProcessorManager)
	{
		$this->productRevisionCreateProcessorManager = $productRevisionCreateProcessorManager;
		$this->productRevisionUpdateProcessorManager = $productRevisionUpdateProcessorManager;
	}

	public function process(array $data = null)
	{
		/** @var \ShopItem $shopItem */
		$shopItem = array_get($data, 'shop_item');
		$inputData = array_get($data, 'input_data');

		$shopCategory = $this->fetchShopCategory($inputData);

		/** @var \ShopItemRevision $shopItemRevision */
		if ($shopItem->canUpdateLatestRevision()) {
			$shopItemRevision = $this->updateShopItemRevision($shopItem->latestRevision(), $shopCategory, $inputData);
			$productRevision = $this->updateProductRevision($shopCategory, $shopItemRevision->productRevision, $inputData);
		} else {
			$productRevision = $this->createProductRevision($shopCategory, $inputData);
			$shopItemRevision = $this->createShopItemRevision($shopItem, $shopCategory, $productRevision, $inputData);
		}

		return [
			'shop_item' => $shopItem,
			'shop_item_revision' => $shopItemRevision,
			'product_revision' => $productRevision,
		];
	}

	protected function updateShopItemRevision(ShopItemRevision $shopItemRevision, \ShopCategory $shopCategory, array $inputData)
	{
		/** @var UpdateShopItemRevisionProcessor $updateProcessor */
		$updateProcessor = App::make(UpdateShopItemRevisionProcessor::class);

		$data = [
			'price' => array_get($inputData, 'general.price'),
			'title' => array_get($inputData, 'general.title'),
			'resource_files' => array_get($inputData, 'upload-file.selectedFiles', []),
			'shop_category' => $shopCategory,
		];

		$updateProcessor->process([
			'shop_item_revision' => $shopItemRevision,
			'input_data' => $data,
		]);

		return $shopItemRevision;
	}

	protected function updateProductRevision(ShopCategory $shopCategory, $productRevision, array $inputData)
	{
		$driverName = Str::camel(implode('-', $shopCategory->getSlugList()));
		$updateProcess = $this->productRevisionUpdateProcessorManager->driver($driverName);

		$updateProcess->process([
			'product_revision' => $productRevision,
			'input_data' => $inputData,
		]);

		return $productRevision;
	}
}
