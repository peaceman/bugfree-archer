<?php
namespace EDM\ShopItem\Processors;

use App;
use EDM\ProcessorInterface;
use EDM\ProductRevision;
use EDM\ShopItemRevision\Processors\CreateShopItemRevision as CreateShopItemRevisionProcess;
use EDM\User\UserInjection;
use Illuminate\Support\Str;
use ShopCategory;
use ShopItem;

class CreateShopItem implements ProcessorInterface
{
	use UserInjection;

	protected $productRevisionCreateProcessorManager;

	public function __construct(ProductRevision\CreateProcessorManager $productRevisionCreateProcessorManager)
	{
		$this->productRevisionCreateProcessorManager = $productRevisionCreateProcessorManager;
	}

	public function process(array $data = null)
	{
		$shopItem = $this->createShopItem();
		$shopCategory = $this->fetchShopCategory($data);

		$productRevision = $this->createProductRevision($shopCategory, $data);
		$shopItemRevision = $this->createShopItemRevision($shopItem, $shopCategory, $productRevision, $data);

		return [
			'shop_item' => $shopItem,
			'shop_item_revision' => $shopItemRevision,
			'product_revision' => $productRevision,
		];
	}

	protected function createShopItem()
	{
		$shopItem = new ShopItem();
		$shopItem->owner()->associate($this->user);

		$shopItem->save();
		return $shopItem;
	}

	/**
	 * @param array $data
	 * @return ShopCategory
	 */
	protected function fetchShopCategory(array $data)
	{
		$shopCategory = ShopCategory::findOrFail(array_get($data, 'general.shop_category_id'));
		return $shopCategory;
	}

	protected function createProductRevision(ShopCategory $shopCategory, array $inputData)
	{
		$driverName = Str::camel(implode('-', $shopCategory->getSlugList()));
		$createProcess = $this->productRevisionCreateProcessorManager->driver($driverName);

		$productRevision = $createProcess->process([
			'shop_category' => $shopCategory,
			'input_data' => $inputData,
		]);

		return $productRevision;
	}

	protected function createShopItemRevision(ShopItem $shopItem, ShopCategory $shopCategory, $productRevision, array $inputData)
	{
		/** @var CreateShopItemRevisionProcess $createProcess */
		$createProcess = App::make(CreateShopItemRevisionProcess::class);

		$shopItemRevision = $createProcess->process([
			'price' => array_get($inputData, 'general.price'),
			'title' => array_get($inputData, 'general.title'),
			'shop_category' => $shopCategory,
			'shop_item' => $shopItem,
			'product_revision' => $productRevision,
			'resource_files' => array_get($inputData, 'upload-file.selectedFiles', []),
		]);

		return $shopItemRevision;
	}
}
