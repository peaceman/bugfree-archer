<?php
namespace EDM\User\Processors;

use EDM\ProcessorInterface;
use EDM\Resource\Storage\StorageDirector;
use EDM\User\UserInjection;
use User;

class DeleteAvatar implements ProcessorInterface
{
	use UserInjection;

	/**
	 * @var \EDM\Resource\Storage\StorageDirector
	 */
	protected $storageDirector;

	public function __construct(StorageDirector $storageDirector)
	{
		$this->storageDirector = $storageDirector;
	}

	public function process(array $data = null)
	{
		$userProfile = $this->user->profile;
		if (!$userProfile->hasAvatar()) {
			return;
		}

		$resourceImage = $userProfile->avatar;

		$this->storageDirector->queueWipingOfResourceFile($resourceImage->originResourceFile);
		foreach ($resourceImage->resourceImageFiles as $resourceImageFile) {
			$this->storageDirector->queueWipingOfResourceFile($resourceImageFile->resourceFile);
		}

		$userProfile->avatar_resource_image_id = null;
		$userProfile->save();
	}
}
