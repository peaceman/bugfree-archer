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

	public function initialStorageTransport(ResourceFile $resourceFile, $filePath, $onlyInstantTransportLocations = false)
	{
		$locationsForInstantTransport = $this->getLocationsForInstantTransport();
		/** @var ResourceLocation[] $locationsForInstantTransport */
		foreach ($locationsForInstantTransport as $resourceLocation) {
			$this->executeStorageTransport($resourceLocation, $resourceFile, $filePath);
		}

		if ($onlyInstantTransportLocations) {
			return;
		}

		$locationsForQueuedTransport = $this->getLocationsForQueuedTransport();
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

		return $this->sortLocationsByUploadOrder($locationsForQueuedStore);
	}

	/**
	 * sorts the given resource locations by upload_order ascending
	 * 
	 * @param  Collection $resourceLocations unsorted collection of resource locations
	 * @return Collection sorted collection of resource locations
	 */
	protected function sortLocationsByUploadOrder(Collection $resourceLocations) {
		return $resourceLocations->sort(function ($a, $b) {
			if ($a->upload_order === $b->upload_order)
				return 0;
			else
				return $a->upload_order < $b->upload_order ? -1 : 1;
		});
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

	public function queueDistributionOfResourceFile(ResourceFile $resourceFile, array $resourceLocationIdsToSkip = null) {
		$locations = is_null($resourceLocationIdsToSkip) 
			? $this->resourceLocations 
			: $this->filterResourceLocationsWithIds($this->resourceLocations, $resourceLocationIdsToSkip);
		$locations = $this->sortLocationsByUploadOrder($locations);

		$locations->each(function ($location) use ($resourceFile) {
			$this->queueStorageTransport($location, $resourceFile);
		});
	}

	/** 
	 * @param Collection $locations
	 * @param array $locationIdsToFilter
	 *
	 * @return Collection
	 */
	protected function filterResourceLocationsWithIds(Collection $locations, array $locationIdsToFilter) {
		return $locations->filter(function ($location) use ($locationIdsToFilter) {
			return !in_array($location->id, $locationIdsToFilter);
		});
	}

	public function queueWipingOfResourceFile(ResourceFile $resourceFile, array $resourceLocationIdsToSkip = null)
	{
		/** @var ResourceFileLocation[] $resourceFileLocations */
		$resourceFileLocations = $resourceFile->resourceFileLocations()->get();
		foreach ($resourceFileLocations as $resourceFileLocation) {
			$skip = $resourceLocationIdsToSkip !== null
				&& !in_array($resourceFileLocation->resource_location_id, $resourceLocationIdsToSkip);
			if ($skip)
				continue;

			if ($resourceFileLocation->resourceLocation->isBackupLocation())
				continue;

			$this->queueDeletionOfResourceFileLocation($resourceFileLocation);
		}
	}

	/**
	 * @param ResourceFileLocation $resourceFileLocation
	 */
	public function queueDeletionOfResourceFileLocation(ResourceFileLocation $resourceFileLocation)
	{
		Queue::push(
			QueueJobHandler::class . '@' . 'deleteFromStorage',
			['resource_file_location_id' => $resourceFileLocation->id]
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
