<?php
use Carbon\Carbon;

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
	protected $table = 'resource_files';

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
		$resourceFileLocation = $this->resourceFileLocations()
			->with(
				[
					'resourceLocation' =>
						function ($query) {
							$query
								->where('state', '=', 'active')
								->orderBy('download_order', 'asc');
						}
				]
			)
			->where('state', '=', ResourceFileLocation::STATE_UPLOADED)
			->first();
		return $resourceFileLocation;
	}

	public function resourceFileLocations()
	{
		return $this->hasMany('ResourceFileLocation');
	}
}
