<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ResourceImageFile
 *
 * @property int $id
 * @property int $resource_image_id
 * @property int $resource_file_id
 * @property string $image_format_identifier
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ResourceImage $resourceImage
 * @property ResourceFile $resourceFile
 *
 * @method static Illuminate\Database\Eloquent\Builder withFormat(string $format)
 */
class ResourceImageFile extends Eloquent
{
	public static $validationRules = [
		'resource_image_id' => ['required', 'exists:resource_images,id'],
		'resource_file_id' => ['required', 'exists:resource_files,id'],
		'image_format_identifier' => ['required'],
	];
	protected $table = 'resource_image_files';

	public function resourceImage()
	{
		return $this->belongsTo('ResourceImage');
	}

	public function resourceFile()
	{
		return $this->belongsTo('ResourceFile');
	}

	public function scopeWithFormat($query, $format)
	{
		return $query->where('image_format_identifier', '=', $format);
	}
}