<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ProjectFileRevision
 *
 * @property int $id
 * @property int $music_genre_id
 * @property int $archive_resource_file_id
 * @property int $sample_resource_file_id
 * @property int $bpm
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property MusicGenre $musicGenre
 * @property ResourceFile $archiveFile
 * @property ResourceFile $sampleFile
 * @property ShopItemRevision $shopItemRevision
 */
class ProjectFileRevision extends Eloquent
{
	public static $validationRules = [
		'music_genre_id' => ['required', 'exists:music_genres,id'],
		'sample_file_id' => ['required', 'exists:resource_files,id'],
		'archive_file_id' => ['required', 'exists:resource_files,id'],
		'bpm' => ['required', 'integer', 'min:0'],
		'description' => ['required'],
	];
	protected $table = 'project_file_revisions';
	protected $fillable = [
		'bpm', 'description',
	];

	public function musicGenre()
	{
		return $this->belongsTo('MusicGenre');
	}

	public function archiveFile()
	{
		return $this->belongsTo('ResourceFile', 'archive_file_id');
	}

	public function sampleFile()
	{
		return $this->belongsTo('ResourceFile', 'sample_file_id');
	}

	public function compatiblePrograms()
	{
		return $this->belongsToMany('MusicProgram', 'project_file_revision_compatible_programs');
	}

	public function compatiblePlugins()
	{
		return $this->belongsToMany('MusicPlugin', 'project_file_revision_compatible_plugins');
	}

	public function compatibleBanks()
	{
		return $this->belongsToMany('MusicPluginBank', 'project_file_revision_compatible_banks', 'project_file_revision_id', 'music_bank_id');
	}

	public function shopItemRevision()
	{
		return $this->morphOne('ShopItemRevision', 'product_revision');
	}
}
