<?php
namespace EDM\Resource\Storage;

use Illuminate\Queue\Jobs\Job;
use ResourceFileLocation;

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
