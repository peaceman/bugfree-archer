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

		$syncData = [];
		foreach ($resourceFiles as $resourceFileInfo) {
			$fileType = $resourceFileInfo['use_as'];
			if (!in_array($fileType, $resourceFileTypes)) {
				continue;
			}

			$resourceFile = \ResourceFile::findOrFail($resourceFileInfo['id']);
			$syncData[$resourceFile->id] = ['file_type' => $fileType];
		}

		$shopItemRevision->resourceFiles()->sync($syncData);
	}
}
