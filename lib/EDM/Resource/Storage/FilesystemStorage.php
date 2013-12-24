<?php
namespace EDM\Resource\Storage;
use Log;

class FilesystemStorage implements StorageInterface
{
	const TYPE = 'filesystem';
	protected $storagePath;

	public function __construct(array $config)
	{
		$this->setStoragePath(array_get($config, 'storage_path'));
		$this->urlPrefix = rtrim(array_get($config, 'url_prefix'), '/');
	}

	public function setStoragePath($storagePath)
	{
		$this->checkStoragePath($storagePath);
		$this->storagePath = rtrim($storagePath, '/');
	}

	public function checkStoragePath($storagePath)
	{
		$checkResult = is_dir($storagePath) && is_writable($storagePath);
		if (!$checkResult) {
			throw new \RuntimeException(sprintf(
				'we have no write access to the given storage path: %s',
				$storagePath
			));
		}
	}

	public function checkResourceFileLocation(\ResourceFileLocation $resourceFileLocation)
	{
		$checkResult = $resourceFileLocation->resourceLocation->type === static::TYPE;
		if (!$checkResult) {
			throw new \LogicException(sprintf(
				'got resource file location object with invalid type: %s for this transport',
				$resourceFileLocation->resourceLocation->type
			));
		}
	}

	public function store(\ResourceFileLocation $resourceFileLocation, $filePath)
	{
		$this->checkResourceFileLocation($resourceFileLocation);
		$this->checkFile($filePath);

		$targetFilePath = $this->generateFilePath($resourceFileLocation->identifier);
		$originFileChecksum = md5_file($filePath);

		$resourceFileLocation->saveWithState(\ResourceFileLocation::STATE_UPLOADING);

		do {
			copy($filePath, $targetFilePath);
			$targetFileChecksum = md5_file($targetFilePath);

			$checksumMismatch = $originFileChecksum !== $targetFileChecksum;
			if ($checksumMismatch) {
				Log::notice(
					'detected checksum mismatch after file copy',
					[
						'paths' => ['origin' => $filePath, 'target' => $targetFilePath],
						'checksums' => ['origin' => $originFileChecksum, 'target' => $targetFileChecksum]
					]
				);
			}
		} while ($checksumMismatch);

		$resourceFileLocation->saveWithState(\ResourceFileLocation::STATE_UPLOADED);
	}

	public function delete(\ResourceFileLocation $resourceFileLocation)
	{
		$this->checkResourceFileLocation($resourceFileLocation);
		$filePath = $this->generateFilePath($resourceFileLocation->identifier);
		$this->checkFile($filePath);

		unlink($filePath);
		$resourceFileLocation->saveWithState(\ResourceFileLocation::STATE_DELETED);
	}

	protected function generateFilePath($identifier)
	{
		return sprintf('%s/%s', $this->storagePath, $identifier);
	}

	public function checkFile($filePath)
	{
		$checkResult = file_exists($filePath)
			&& is_file($filePath)
			&& is_readable($filePath);

		if (!$checkResult) {
			throw new \RuntimeException(sprintf(
				'we have no read access to the given file path: %s',
				$filePath
			));
		}
	}

	public function getUrl(\ResourceFileLocation $resourceFileLocation)
	{
		return asset($this->urlPrefix . '/' . $resourceFileLocation->identifier);
	}
}
