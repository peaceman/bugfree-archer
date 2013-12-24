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

	public function initialStore(ResourceFile $resourceFile, $filePath)
	{
		$locationsForInstantStore = array_filter(
			$this->resourceLocations,
			function ($resourceLocation) {
				/** @var ResourceLocation $resourceLocation */
				return $resourceLocation->qualifiesForInstantTransport();
			}
		);

		$locationsForQueuedTransport = $this->getLocationsForQueuedTransport();

		/** @var ResourceLocation[] $locationsForInstantStore */
		foreach ($locationsForInstantStore as $resourceLocation) {
			$this->executeStorageTransport($resourceLocation, $resourceFile, $filePath);
		}

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

	protected function executeStorageTransport(ResourceLocation $resourceLocation, ResourceFile $resourceFile, $filePath)
	{
		$storage = $this->storagesByType[$resourceLocation->type];
		$resourceFileLocation = $this->createResourceFileLocation($resourceLocation, $resourceFile);
		$storage->store($resourceFileLocation, $filePath);
	}

	protected function createResourceFileLocation(ResourceLocation $resourceLocation, ResourceFile $resourceFile)
	{
		$storage = $this->storagesByType[$resourceLocation->type];
		$resourceFileLocation = ResourceFileLocation::create(
			[
				'resource_file_id' => $resourceFile->id,
				'resource_location_id' => $resourceLocation->id,
				'identifier' => $storage->getNewFileIdentifier(),
			]
		);

		return $resourceFileLocation;
	}

	protected function queueStorageTransport(ResourceLocation $resourceLocation, ResourceFile $resourceFile)
	{
		$resourceFileLocation = $this->createResourceFileLocation($resourceLocation, $resourceFile);
		Queue::push(QueueJobHandler::class, ['resource_file_location_id' => $resourceFileLocation->id]);
	}
}
