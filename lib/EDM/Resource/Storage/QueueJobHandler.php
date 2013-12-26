<?php
namespace EDM\Resource\Storage;

use Illuminate\Queue\Jobs\Job;
use Queue;
use ResourceFile;
use ResourceFileLocation;
use ResourceLocation;

class QueueJobHandler
{
	/**
	 * @param Job $job
	 * @param array $data
	 */
	public function deleteResourceFileLocation($job, $data)
	{
		/** @var ResourceFileLocation $resourceFileLocation */
		$resourceFileLocation = ResourceFileLocation::findOrFail($data['resource_file_location_id']);
		$storage = $resourceFileLocation->resourceLocation->getStorage();

		$result = $storage->delete($resourceFileLocation);

		if ($result) {
			$job->delete();
		} else {
			$job->release();
		}
	}

	/**
	 * @param Job $job
	 * @param array $data
	 */
	public function fillResourceLocation($job, $data)
	{
		/** @var ResourceLocation $resourceLocation */
		$resourceLocation = ResourceLocation::findOrFail($data['resource_location_id']);

		ResourceFile::chunk(
			Config::get('storage.resource_file.chunk_size', 100),
			function ($resourceFiles) use ($resourceLocation) {
				/** @var $resourceLocation ResourceLocation */
				/** @var ResourceFile $resourceFile */
				foreach ($resourceFiles as $resourceFile) {
					$resourceFileLocation = $resourceFile->getOrCreateResourceFileLocationForResourceLocation($resourceLocation);
					if (!$resourceFileLocation->needsUpload()) {
						continue;
					}

					Queue::push(
						__CLASS__ . '@transportToStorage',
						['resource_file_location_id' => $resourceFileLocation->id]
					);
				}
			}
		);

		$job->delete();
	}

	/**
	 * @param Job $job
	 * @param array $data
	 */
	public function transportToStorage($job, $data)
	{
		/** @var ResourceFileLocation $targetFileLocation */
		$targetFileLocation = ResourceFileLocation::findOrFail($data['resource_file_location_id']);
		$targetStorage = $targetFileLocation->resourceLocation->getStorage();

		$result = $targetStorage->store(
			$targetFileLocation,
			$targetFileLocation->resourceFile->fetchLocalFilesystemPath()
		);

		if ($result) {
			$job->delete();
		} else {
			$job->release();
		}
	}

	/**
	 * @param Job $job
	 * @param array $data
	 */
	public function wipeResourceLocation($job, $data)
	{
	}
}
