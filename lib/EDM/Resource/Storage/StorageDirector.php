<?php
namespace EDM\Resource\Storage;

use Queue;
use ResourceFile;
use ResourceFileLocation;
use ResourceLocation;

class StorageDirector
{
	/**
	 * @var \ResourceLocations[]
	 */
	protected $resourceLocations = [];
	/**
	 * @var StorageInterface[]
	 */
	protected $storagesByType = [];

	/**
	 * @param \ResourceLocation[] $resourceLocations
	 * @param StorageInterface[] $storages
	 */
	public function __construct(array $resourceLocations, array $storages)
	{
		$this->setResourceLocations($resourceLocations);
		$this->setStorages($storages);
	}

	/**
	 * @param \ResourceLocation[] $resourceLocations
	 */
	public function setResourceLocations(array $resourceLocations)
	{
		$this->resourceLocations = [];
		foreach ($resourceLocations as $resourceLocation) {
			$this->resourceLocations[$resourceLocation->type] = $resourceLocation;
		}
	}

	/**
	 * @param StorageInterface[] $storages
	 */
	public function setStorages(array $storages)
	{
		$this->storagesByType = [];
		foreach ($storages as $storage) {
			$this->storagesByType[$storage->getType()] = $storage;
		}
	}

	public function initialStorageTransport(ResourceFile $resourceFile, $filePath)
	{
		$locationsForInstantTransport = $this->getLocationsForInstantTransport();
		$locationsForQueuedTransport = $this->getLocationsForQueuedTransport();

		/** @var ResourceLocation[] $locationsForInstantTransport */
		foreach ($locationsForInstantTransport as $resourceLocation) {
			$this->executeStorageTransport($resourceLocation, $resourceFile, $filePath);
		}

		/** @var ResourceLocation[] $locationsForQueuedTransport */
		foreach ($locationsForQueuedTransport as $resourceLocation) {
			$this->queueStorageTransport($resourceLocation, $resourceFile);
		}
	}

	/**
	 * @return array
	 */
	protected function getLocationsForQueuedTransport()
	{
		$locationsForQueuedStore = array_filter(
			$this->resourceLocations,
			function ($resourceLocation) {
				/** @var ResourceLocation $resourceLocation */
				return !$resourceLocation->qualifiesForInstantTransport();
			}
		);

		uasort(
			$locationsForQueuedStore,
			function ($a, $b) {
				if ($a->priority === $b->priority) return 0;
				return $a->priority < $b->priority ? -1 : 1;
			}
		);
		return $locationsForQueuedStore;
	}

	public function executeStorageTransport(ResourceLocation $resourceLocation, ResourceFile $resourceFile, $filePath)
	{
		$storage = $this->storagesByType[$resourceLocation->type];
		$resourceFileLocation = $this->createOrFindResourceFileLocation($resourceLocation, $resourceFile);
		$storage->store($resourceFileLocation, $filePath);
	}

	protected function createOrFindResourceFileLocation(ResourceLocation $resourceLocation, ResourceFile $resourceFile)
	{
		$storage = $this->storagesByType[$resourceLocation->type];
		/** @var ResourceFileLocation $resourceFileLocation */
		$resourceFileLocation = ResourceFileLocation::firstOrNew(
			[
				'resource_file_id' => $resourceFile->id,
				'resource_location_id' => $resourceLocation->id,
			]
		);

		if (!$resourceFileLocation->exists) {
			$resourceFileLocation->identifier = $storage->getNewFileIdentifier();
			$resourceFileLocation->save();
		}

		return $resourceFileLocation;
	}

	protected function queueStorageTransport(ResourceLocation $resourceLocation, ResourceFile $resourceFile)
	{
		$resourceFileLocation = $this->createOrFindResourceFileLocation($resourceLocation, $resourceFile);
		Queue::push(
			QueueJobHandler::class . '@' . 'transportToStorage',
			['resource_file_location_id' => $resourceFileLocation->id]
		);
	}

	public function queueWipingOfResourceFile(ResourceFile $resourceFile, array $resourceLocationIds = null)
	{
		/** @var ResourceFileLocation[] $resourceFileLocations */
		$resourceFileLocations = $resourceFile->resourceFileLocations()->all();
		foreach ($resourceFileLocations as $resourceFileLocation) {
			$skip = $resourceLocationIds !== null
				&& !in_array($resourceFileLocation->resource_location_id, $resourceLocationIds);
			if ($skip)
				continue;

			if ($resourceFileLocation->resourceLocation->isBackupLocation())
				continue;

			$this->queueDeletionOfResourceFileLocation($resourceFileLocation);
		}
	}

	public function queueFillingOfResourceLocation(ResourceLocation $resourceLocation)
	{
		Queue::push(
			QueueJobHandler::class . '@' . 'fillResourceLocation',
			['resource_location_id' => $resourceLocation->id]
		);
	}

	public function queueWipingOfResourceLocation(ResourceLocation $resourceLocation)
	{
		Queue::push(
			QueueJobHandler::class . '@' . 'wipeResourceLocation',
			['resource_location_id' => $resourceLocation->id]
		);
	}

	/**
	 * @param $resourceFileLocation
	 */
	public function queueDeletionOfResourceFileLocation($resourceFileLocation)
	{
		Queue::push(
			QueueJobHandler::class . '@' . 'deleteResourceFileLoction',
			['resource_file_location' => $resourceFileLocation->id]
		);
	}

	/**
	 * @return array
	 */
	protected function getLocationsForInstantTransport()
	{
		$locationsForInstantTransport = array_filter(
			$this->resourceLocations,
			function ($resourceLocation) {
				/** @var ResourceLocation $resourceLocation */
				return $resourceLocation->qualifiesForInstantTransport();
			}
		);
		return $locationsForInstantTransport;
	}
}
