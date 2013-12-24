<?php
namespace EDM\Resource\Storage;

use Illuminate\Database\Eloquent\Collection;
use Queue;
use ResourceFile;
use ResourceFileLocation;
use ResourceLocation;

class StorageDirector
{
	/**
	 * @var Collection|\ResourceLocation[]
	 */
	protected $resourceLocations = [];

	/**
	 * @param \ResourceLocation[] $resourceLocations
	 */
	public function __construct($resourceLocations)
	{
		$this->resourceLocations = $resourceLocations;
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
	 * @return Collection|\ResourceLocation[]
	 */
	protected function getLocationsForInstantTransport()
	{
		return $this->resourceLocations->filter(
			function ($resourceLocation) {
				/** @var ResourceLocation $resourceLocation */
				return $resourceLocation->qualifiesForInstantTransport();
			}
		);
	}

	/**
	 * @return Collection|\ResourceLocation[]
	 */
	protected function getLocationsForQueuedTransport()
	{
		$locationsForQueuedStore = $this->resourceLocations->filter(
			function ($resourceLocation) {
				/** @var ResourceLocation $resourceLocation */
				return !$resourceLocation->qualifiesForInstantTransport();
			}
		);

		$locationsForQueuedStore = $locationsForQueuedStore->sort(
			function ($a, $b) {
				if ($a->priority === $b->priority) return 0;
				return $a->priority < $b->priority ? -1 : 1;
			}
		);

		return $locationsForQueuedStore;
	}

	public function executeStorageTransport(ResourceLocation $resourceLocation, ResourceFile $resourceFile, $filePath)
	{
		$storage = $resourceLocation->getStorage();
		$resourceFileLocation = $this->createOrFindResourceFileLocation($resourceLocation, $resourceFile);
		$storage->store($resourceFileLocation, $filePath);
	}

	protected function createOrFindResourceFileLocation(ResourceLocation $resourceLocation, ResourceFile $resourceFile)
	{
		$storage = $resourceLocation->getStorage();
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

	/**
	 * @param $resourceFileLocation
	 */
	public function queueDeletionOfResourceFileLocation($resourceFileLocation)
	{
		Queue::push(
			QueueJobHandler::class . '@' . 'deleteResourceFileLocation',
			['resource_file_location' => $resourceFileLocation->id]
		);
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
}
