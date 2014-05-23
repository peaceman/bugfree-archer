<?php
namespace EDM\ResourceImage;

use EDM\Common\Injections\StorageDirectorInjection;

class UrlHelper
{
	use StorageDirectorInjection;

	/**
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * @var \Intervention\Image\ImageManager
	 */
	protected $imageManager;

	public function __construct(\Illuminate\Config\Repository $config, \Intervention\Image\ImageManager $imageManager)
	{
		$this->config = $config;
		$this->imageManager = $imageManager;
	}

	public function create(\Illuminate\View\View $view)
	{
		$view['imageUrls'] = $this;
	}

	public function getUrlForFormat(\ResourceImage $resourceImage, $format)
	{
		$formatDefinition = $this->config->get("image.format.$format");
		if (!$formatDefinition) {
			throw new \LogicException("Unknown image url format: '$format'. Check your config!");
		}

		/** @var \ResourceImageFile $resourceImageFile */
		$resourceImageFile = $resourceImage->fetchResourceImageFileForFormat($format);
		if (!$resourceImageFile) {
			$resourceImageFile = $this->createResourceImageFileForFormat($resourceImage, $format, $formatDefinition);
		}

		$resourceFile = $resourceImageFile->resourceFile;
		return $resourceFile->getUrl();
	}

	protected function createResourceImageFileForFormat(\ResourceImage $resourceImage, $format, array $formatDefinition)
	{
		$originResourceFile = $resourceImage->originResourceFile;
		/** @var \Intervention\Image\Image $image */
		$image = $this->imageManager->make($originResourceFile->fetchLocalFilesystemPath());

		$width = array_get($formatDefinition, 'width');
		$height = array_get($formatDefinition, 'height');

		/** @var \Intervention\Image\Image $resizedImage */
		$resizedImage = $image->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		});

		$tmpFilePath = rtrim(sys_get_temp_dir(), '/') . '/' . uniqid($image->filename) . '.' . $image->extension;
		$resizedImage->save($tmpFilePath);

		$resizedResourceFile = new \ResourceFile([
			'protected' => $originResourceFile->protected,
			'original_name' => $originResourceFile->original_name,
			'mime_type' => $originResourceFile->mime_type,
			'size' => filesize($tmpFilePath),
		]);
		$resizedResourceFile->userTrackingSession()->associate($originResourceFile->userTrackingSession);
		$resizedResourceFile->save();

		$this->storageDirector->initialStorageTransport($resizedResourceFile, $tmpFilePath);
		unlink($tmpFilePath);

		$resourceImageFile = new \ResourceImageFile();
		$resourceImageFile->image_format_identifier = $format;
		$resourceImageFile->resourceImage()->associate($resourceImage);
		$resourceImageFile->resourceFile()->associate($resizedResourceFile);
		$resourceImageFile->save();

		return $resourceImageFile;
	}
}