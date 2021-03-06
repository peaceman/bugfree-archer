<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ResourceImage
 *
 * @property int $id
 * @property int $origin_resource_file_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ResourceFile $originResourceFile
 * @property ResourceImageFile[] $resourceImageFiles
 */
class ResourceImage extends Eloquent
{
	public static $validationRules = [
		'origin_resource_file_id' => ['required', 'exists:resource_files,id'],
	];
	protected $table = 'resource_images';

	public function originResourceFile()
	{
		return $this->belongsTo('ResourceFile', 'origin_resource_file_id');
	}

	public function resourceImageFiles()
	{
		return $this->hasMany('ResourceImageFile');
	}

	public function fetchResourceImageFileForFormat($format)
	{
		$resourceImageFile = $this->resourceImageFiles()
			->withFormat($format)
			->first();

		return $resourceImageFile;
	}
}