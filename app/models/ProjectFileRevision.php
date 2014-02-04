<?php
use Carbon\Carbon;

/**
 * Class ProjectFileRevision
 *
 * @property int $id
 * @property int $music_genre_id
 * @property int $archive_resource_file_id
 * @property int $sample_resource_file_id
 * @property int $bpm
 * @property string $description
 *
 * @property MusicGenre $musicGenre
 * @property ResourceFile $archiveFile
 * @property ResourceFile $sampleFile
 */
class ProjectFileRevision extends Eloquent
{
	protected $table = 'project_file_revisions';

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
		return $this->belongsToMany('MusicBank', 'project_file_revision_compatible_banks');
	}

	public function shopItemRevision()
	{
		return $this->morphOne('ShopItemRevision', 'productRevision');
	}
}
