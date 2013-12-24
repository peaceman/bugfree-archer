<?php
use Carbon\Carbon;

/**
 * Class ResourceFile
 *
 * @property int $id
 * @property bool $protected
 * @property string $original_name
 * @property int $size
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ResourceFile extends Eloquent
{
	protected $table = 'resource_files';

	public function resourceFileLocations()
	{
		return $this->hasMany('ResourceFileLocation');
	}
}
