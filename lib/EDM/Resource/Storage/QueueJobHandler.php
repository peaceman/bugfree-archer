<?php
namespace EDM\Resource\Storage;

use Illuminate\Queue\Jobs\Job;

class QueueJobHandler
{
	/**
	 * @param Job $job
	 * @param array $data
	 */
	public function deleteResourceFileLocation($job, $data)
	{
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
	}

	/**
	 * @param Job $job
	 * @param array $data
	 */
	public function wipeResourceLocation($job, $data)
	{
	}
}
