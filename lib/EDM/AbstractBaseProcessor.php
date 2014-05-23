<?php
namespace EDM;

abstract class AbstractBaseProcessor implements ProcessorInterface
{
	/**
	 * @param \EDM\ValidatorInterface[] $validators
	 * @param object $model
	 */
	protected function executeValidatorsOnModel(array $validators, $model)
	{
		foreach ($validators as $validator) {
			$validator->validate($model);
		}
	}

	/**
	 * @param array $source
	 * @param $key
	 * @throws Common\Exception\MissingParameter
	 * @internal param null $default
	 * @return mixed
	 */
	protected function requireData(array $source, $key)
	{
		$result = array_get($source, $key, null);

		if (is_null($result)) {
			throw new \EDM\Common\Exception\MissingParameter($source, $key);
		}

		return $result;
	}

	protected function storeResourceFileAssociationsOfProduct(\ShopItemRevision $shopItemRevision, \ProductRevisionInterface $productRevision, array $resourceFiles)
	{
		$resourceFileTypes = $productRevision->getResourceFileTypes();

		$syncData = [
			'generic' => [],
			'images' => [],
		];

		foreach ($resourceFiles as $resourceFileInfo) {
			$fileType = $resourceFileInfo['use_as'];
			if (!in_array($fileType, $resourceFileTypes)) {
				continue;
			}

			/** @var \ResourceFile $resourceFile */
			$resourceFile = \ResourceFile::findOrFail($resourceFileInfo['id']);
			$resourceImage = \ResourceImage::where('origin_resource_file_id', '=', $resourceFile->id)
				->first();

			if ($resourceImage) {
				$syncData['images'][$resourceImage->id] = ['image_type' => $fileType];
			} else {
				$syncData['generic'][$resourceFile->id] = ['file_type' => $fileType];
			}
		}

		$shopItemRevision->resourceFiles()->sync($syncData['generic']);
		$shopItemRevision->resourceImages()->sync($syncData['images']);
	}
}
