<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ResourceLocation
 *
 * @property int $id
 * @property string $type
 * @property string $state
 * @property bool $is_backup
 * @property int $upload_order
 * @property int $download_order
 * @property array $settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ResourceLocation extends Eloquent
{
	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'inactive';
	const STATE_ONLY_UPLOAD = 'only_upload';
	protected $table = 'resource_locations';

	public function resourceFileLocations()
	{
		return $this->hasMany('ResourceFileLocation');
	}

	public function resourceFiles()
	{
		return $this->belongsToMany('ResourceFile', 'resource_file_locations');
	}

	public function qualifiesForInstantTransport()
	{
		return $this->upload_order === Config::get('storage.instant_transport_upload_order');
	}

	public function isBackupLocation()
	{
		return $this->is_backup;
	}

	public function getStorage()
	{
		/** @var \EDM\Resource\Storage\StorageInterface $storage */
		$storage = App::make('storage.' . $this->type);

		$settings = $this->settings;
		if (!empty($settings)) {
			$storage->setSettings(json_decode($settings, true));
		}

		return $storage;
	}

	public function getAmountOfFiles()
	{
		return $this->resourceFileLocations()
			->where('state', \ResourceFileLocation::STATE_UPLOADED)
			->count();
	}

	public function getSpaceUsage()
	{
		return $this->resourceFiles()
			->where('state', \ResourceFileLocation::STATE_UPLOADED)
			->sum('resource_files.size');
	}
}
