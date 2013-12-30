<?php
namespace EDM\Resource\Storage;

use Aws\Common\Aws;
use Aws\S3\S3Client;
use Config;
use Log;

class AWSStorage implements StorageInterface
{
	const TYPE = 'aws';
	/**
	 * @var array
	 */
	protected $config;
	/**
	 * @var \Aws\Common\Aws
	 */
	protected $aws;

	public function __construct(array $config, Aws $aws)
	{
		$this->config = $config;
		$this->aws = $aws;
	}

	public function store(\ResourceFileLocation $resourceFileLocation, $filePath)
	{
		/** @var S3Client $s3 */
		$s3 = $this->aws->get('s3');
		$originalResourceFileLocationState = $resourceFileLocation->state;

		try {
			$resourceFileLocation->saveWithState(\ResourceFileLocation::STATE_UPLOADING);
			$s3->upload(
				$this->config['bucket'],
				$resourceFileLocation->identifier,
				fopen($filePath, 'r'),
				$resourceFileLocation->resourceFile->protected ? 'private' : 'public-read'
			);

			$resourceFileLocation->saveWithState(\ResourceFileLocation::STATE_UPLOADED);
			return true;
		} catch (\Exception $e) {
			Log::error(
				'upload to amazon s3 failed',
				['e' => $e, 'filePath' => $filePath, 'resourceFileLocation' => $resourceFileLocation->toArray()]
			);

			$resourceFileLocation->saveWithState($originalResourceFileLocationState);
			return false;
		}
	}

	public function delete(\ResourceFileLocation $resourceFileLocation)
	{
		/** @var S3Client $s3 */
		$s3 = $this->aws->get('s3');

		try {
			$s3->deleteObject(
				[
					'Bucket' => $this->config['bucket'],
					'Key' => $resourceFileLocation->identifier,
				]
			);
			$resourceFileLocation->saveWithState(\ResourceFileLocation::STATE_DELETED);
			return true;
		} catch (\Exception $e) {
			Log::error(
				'delete on amazon s3 failed',
				['e' => $e, 'resourceFileLocation' => $resourceFileLocation->toArray()]
			);

			return false;
		}
	}

	public function getProtectedUrl(\ResourceFileLocation $resourceFileLocation)
	{
		/** @var S3Client $s3 */
		$s3 = $this->aws->get('s3');

		try {
			return $s3->getObjectUrl(
				$this->config['bucket'],
				$resourceFileLocation->identifier,
				'+' . Config::get('storage.protected_url_lifetime_in_minutes') . ' minutes'
			);
		} catch (\Exception $e) {
			Log::error(
				'protected getObjectUrl on amazon s3 failed',
				['e' => $e, 'resourceFileLocation' => $resourceFileLocation->toArray()]
			);
			return false;
		}
	}

	public function getUrl(\ResourceFileLocation $resourceFileLocation)
	{
		/** @var S3Client $s3 */
		$s3 = $this->aws->get('s3');

		try {
			return $s3->getObjectUrl($this->config['bucket'], $resourceFileLocation->identifier);
		} catch (\Exception $e) {
			Log::error(
				'getObjectUrl on amazon s3 failed',
				['e' => $e, 'resourceFileLocation' => $resourceFileLocation->toArray()]
			);
			return false;
		}
	}

	public function getNewFileIdentifier()
	{
		return uniqid();
	}

	public function getType()
	{
		return static::TYPE;
	}

	public function setSettings(array $settings)
	{
		$this->config = $settings;
	}
}
