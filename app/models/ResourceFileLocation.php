<?php
use Carbon\Carbon;

/**
 * Class ResourceFileLocation
 *
 * @property int $id
 * @property int $resource_file_id
 * @property ResourceFile $resourceFile
 * @property int $resource_location_id
 * @property ResourceLocation $resourceLocation
 * @property string $identifier
 * @property string $state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ResourceFileLocation extends Eloquent
{
	const STATE_NEW = 'new';
	const STATE_UPLOADING = 'uploading';
	const STATE_UPLOADED = 'uploaded';
	const STATE_DELETED = 'deleted';
	protected $table = 'resource_file_locations';
	public static $unguarded = true;

	public function resourceLocation()
	{
		return $this->belongsTo('ResourceLocation');
	}

	public function resourceFile()
	{
		return $this->belongsTo('ResourceFile');
	}

	public function resourceFileDownloads()
	{
		return $this->hasMany('ResourceFileDownloads');
	}

	public function saveWithState($newState)
	{
		$this->state = $newState;
		$this->save();
	}
}
