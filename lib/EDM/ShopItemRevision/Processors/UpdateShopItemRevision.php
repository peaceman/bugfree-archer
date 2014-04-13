<?php
namespace EDM\ShopItemRevision\Processors;

class UpdateShopItemRevision extends AbstractBaseProcessor
{
	public function process(array $data = null)
	{
		/** @var \ShopItemRevision $shopItemRevision */
		$shopItemRevision = array_get($data, 'shop_item_revision');
		$inputData = array_get($data, 'input_data', []);

		$shopItemRevision->fill(array_only($inputData, ['title', 'price']));
		if (isset($inputData['shop_category'])) {
			$shopItemRevision->shopCategory()->associate($inputData['shop_category']);
		}

		$this->executeValidatorsOnShopItemRevision($this->validatorBag->preSave, $shopItemRevision);
		$shopItemRevision->save();
		$this->executeValidatorsOnShopItemRevision($this->validatorBag->postSave, $shopItemRevision);

		return $shopItemRevision;
	}

}