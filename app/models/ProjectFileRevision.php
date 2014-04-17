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

	public function shopItemRevision()
	{
		return $this->morphOne('ShopItemRevision', 'product_revision');
	}

	public function getMetaData()
	{
		return [
			'bpm' => $this->bpm,
			'music_genre' => $this->musicGenre->name,
			'music_programs' => implode(', ', $this->getNamesOfCompatibleMusicPrograms()),
			'music_plugins' => implode(', ', $this->getNamesOfCompatibleMusicPlugins()),
			'music_banks' => implode(', ', $this->getNamesOfCompatibleMusicPluginBanks()),
		];
	}

	public function generateStepData()
	{
		$archiveFileData = $this->archiveFile->toArray();
		$archiveFileData['use_as'] = 'archive';

		$sampleFileData = $this->sampleFile->toArray();
		$sampleFileData['use_as'] = 'sample';

		$stepData = [
			'upload-file' => [
				'state' => 'done',
				'inputData' => [
					'selectedFiles' => [
						$archiveFileData,
						$sampleFileData,
					],
				],
			],
			'project-file' => [
				'state' => 'done',
				'inputData' => [
					'bpm' => $this->bpm,
					'description' => $this->description,
					'music_genre_id' => $this->music_genre_id,
					'music_plugin_bank_ids' => $this->compatibleBanks()->get(['id'])->lists('id'),
					'music_plugin_ids' => $this->compatiblePlugins()->get(['id'])->lists('id'),
					'music_program_ids' => $this->compatiblePrograms()->get(['id'])->lists('id'),
				],
			],
		];

		return $stepData;
	}

	public function compatibleBanks()
	{
		return $this->belongsToMany('MusicPluginBank', 'project_file_revision_compatible_banks', 'project_file_revision_id', 'music_bank_id');
	}

	public function compatiblePlugins()
	{
		return $this->belongsToMany('MusicPlugin', 'project_file_revision_compatible_plugins');
	}

	public function compatiblePrograms()
	{
		return $this->belongsToMany('MusicProgram', 'project_file_revision_compatible_programs');
	}

	public function getNamesOfCompatibleMusicPrograms()
	{
		return $this->compatiblePrograms()->lists('name');
	}

	public function getNamesOfCompatibleMusicPlugins()
	{
		return $this->compatiblePlugins()->lists('name');
	}

	public function getNamesOfCompatibleMusicPluginBanks()
	{
		return $this->compatibleBanks()->lists('name');
	}

	public function getFiles()
	{
		return [
			['use_as' => 'sample', 'file' => $this->sampleFile],
			['use_as' => 'archive', 'file' => $this->archiveFile],
		];
	}
}
