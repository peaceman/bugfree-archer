<?php
namespace EDM\ResourceLocation\Processors;

use EDM\AbstractBaseProcessor;
use EDM\ValidatorBagInjection;

class UpdateResourceLocation extends AbstractBaseProcessor
{
	use \EDM\Common\Injections\StorageDirectorInjection;
	use ValidatorBagInjection;

	public function process(array $data = null)
	{
		$resourceLocation = $this->requireData($data, 'resource_location');
		$inputData = $this->requireData($data, 'input_data');

		$newState = array_get($inputData, 'state', false);
		array_forget($inputData, 'state');

		$resourceLocation->fill($inputData);

		$this->executeValidatorsOnModel($this->validatorBag->preSave, $resourceLocation);
		$resourceLocation->save();
		$this->executeValidatorsOnModel($this->validatorBag->postSave, $resourceLocation);

		if ($newState) {
			$this->changeState($resourceLocation, $newState);
		}
	}

	protected function changeState(\ResourceLocation $resourceLocation, $newState)
	{
		if ($newState === $resourceLocation->state) {
			return;
		}

		$resourceLocation->state = $newState;
		$this->executeValidatorsOnModel($this->validatorBag->preSave, $resourceLocation);
		$resourceLocation->save();

		switch ($newState) {
			case \ResourceLocation::STATE_ACTIVE:
				$this->storageDirector->queueFillingOfResourceLocation($resourceLocation);
				break;
			case \ResourceLocation::STATE_INACTIVE:
				if (!$resourceLocation->isBackupLocation()) {
					$this->storageDirector->queueWipingOfResourceLocation($resourceLocation);
				}
				break;
		}
	}

}
