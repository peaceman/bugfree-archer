<?php
use Carbon\Carbon;
use EDM\Resource\Storage\FilesystemStorage;
use EDM\ModelTraits;

/**
 * Class ResourceFile
 *
 * @property int $id
 * @property bool $protected
 * @property string $original_name
 * @property string $mime_type
 * @property int $size
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ResourceFile extends Eloquent
{
	use ModelTraits\UserTrackingSessionAware;
	protected $table = 'resource_files';
	protected $fillable = ['protected', 'original_name', 'mime_type', 'size'];
	protected $hidden = ['userTrackingSession', 'user_tracking_session_id'];

	/**
	 * @return string|null
	 */
	public function getUrl()
	{
		$resourceFileLocation = $this->fetchResourceFileLocationForDownload();
		if ($resourceFileLocation === null) {
			Log::error(
				"can't find downloadable resource file location",
				[
					'resource_file' => $this->toArray(),
				]
			);
			return null;
		}

		return $resourceFileLocation->getUrl();
	}

	/**
	 * @return ResourceFileLocation|null
	 */
	protected function fetchResourceFileLocationForDownload()
	{
		$query = DB::table('resource_file_locations')
			->join('resource_locations', 'resource_file_locations.resource_location_id', '=', 'resource_locations.id')
			->where('resource_file_locations.resource_file_id', '=', $this->id)
			->where('resource_locations.state', '=', ResourceLocation::STATE_ACTIVE)
			->where('resource_file_locations.state', '=', ResourceFileLocation::STATE_UPLOADED)
			->orderBy('resource_locations.download_order', 'asc')
			->groupBy('resource_file_locations.id')
			->limit(1);

		$result = $query->select('resource_file_locations.id')->pluck('id');
		return ResourceFileLocation::findOrFail($result);
	}

	/**
	 * @throws RuntimeException
	 * @return string
	 */
	public function fetchLocalFilesystemPath()
	{
		$resourceLocationFilterClosure = function ($query) {
			$query->where('type', '=', FilesystemStorage::TYPE)
				->where('state', '!=', ResourceLocation::STATE_INACTIVE);
		};

		/** @var \ResourceFileLocation $localResourceFileLocation */
		$localResourceFileLocation = $this->resourceFileLocations()
			->with(['resourceLocation' => $resourceLocationFilterClosure])
			->where('state', '=', ResourceFileLocation::STATE_UPLOADED)
			->first();

		if (!$localResourceFileLocation) {
			throw new RuntimeException(sprintf(
				"Can't examine local filesystem path of resource file with id: %d",
				$this->id
			));
		}

		/** @var FilesystemStorage $localStorage */
		$localStorage = $localResourceFileLocation->resourceLocation->getStorage();
		return $localStorage->generateFilePath($localResourceFileLocation->identifier);
	}

	public function resourceFileLocations()
	{
		return $this->hasMany('ResourceFileLocation');
	}

	/**
	 * @param ResourceLocation $resourceLocation
	 *
	 * @return ResourceFileLocation
	 */
	public function getOrCreateResourceFileLocationForResourceLocation($resourceLocation)
	{
		$fileLocation = $this->resourceFileLocations()
			->where('resource_location_id', '=', $resourceLocation->id)
			->first();

		if (!$fileLocation) {
			$locationStorage = $resourceLocation->getStorage();
			$fileLocation = ResourceFileLocation::create(
				[
					'resource_location_id' => $resourceLocation->id,
					'resource_file_id' => $this->id,
					'identifier' => $locationStorage->getNewFileIdentifier(),
				]
			);
		}

		return ResourceFileLocation::find($fileLocation->id);
	}
}
