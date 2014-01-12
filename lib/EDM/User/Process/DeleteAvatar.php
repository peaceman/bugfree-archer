<?php
namespace EDM\User\Process;

use EDM\Resource\Storage\StorageDirector;
use User;

class DeleteAvatar extends AbstractUserProcess
{
	/**
	 * @var \EDM\Resource\Storage\StorageDirector
	 */
	protected $storageDirector;

	public function __construct(User $user, StorageDirector $storageDirector)
	{
		parent::__construct($user);

		$this->storageDirector = $storageDirector;
	}

	public function process(array $data = null)
	{
		$userProfile = $this->user->profile;
		if (!$userProfile->hasAvatar()) {
			return;
		}

		$this->storageDirector->queueWipingOfResourceFile($userProfile->avatar);
		$userProfile->picture_file_id = null;
		$userProfile->save();
	}
}
