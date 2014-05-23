<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

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

	public function getFileName()
	{
		$fileName = $this->identifier;
		$extension = $this->resourceFile->getFileExtension();

		if (!empty($extension)) {
			$fileName .= ".$extension";
		}

		return $fileName;
	}

	public function getUrl()
	{
		$storage = $this->resourceLocation->getStorage();
		return $this->resourceFile->protected ?
			$storage->getProtectedUrl($this) :
			$storage->getUrl($this);
	}

	/**
	 * @return bool
	 */
	public function needsUpload()
	{
		return in_array($this->state, [static::STATE_NEW, static::STATE_DELETED]);
	}
}
