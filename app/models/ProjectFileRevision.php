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
 * @property ResourceFile $sampleFile
 * @property ShopItemRevision $shopItemRevision
 */
class ProjectFileRevision extends Eloquent implements ProductRevisionInterface
{
	public static $validationRules = [
		'music_genre_id' => ['required', 'exists:music_genres,id'],
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

	public function shopItemRevision()
	{
		return $this->morphOne('ShopItemRevision', 'product_revision');
	}

	public function getMetaData()
	{
		return [
			'bpm' => $this->bpm,
			'music_genre' => $this->musicGenre->name,
			'music_programs' => implode(', ', $this->getNamesOfAcceptedCompatibleMusicPrograms()),
			'music_plugins' => implode(', ', $this->getNamesOfAcceptedCompatibleMusicPlugins()),
			'music_plugin_banks' => implode(', ', $this->getNamesOfAcceptedCompatibleMusicPluginBanks()),
		];
	}

	public function generateStepData()
	{
		$stepData = [
			'upload-file' => [
				'state' => 'done',
				'inputData' => [
					'selectedFiles' => $this->getFilesForStepData(),
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
		return $this->compatiblePrograms()
			->lists('name');
	}

	public function getNamesOfCompatibleMusicPlugins()
	{
		return $this->compatiblePlugins()
			->lists('name');
	}

	public function getNamesOfCompatibleMusicPluginBanks()
	{
		return $this->compatibleBanks()
			->lists('name');
	}

	public function getNamesOfAcceptedCompatibleMusicPrograms()
	{
		return $this->compatiblePrograms()
			->accepted()
			->lists('name');
	}

	public function getNamesOfAcceptedCompatibleMusicPlugins()
	{
		return $this->compatiblePlugins()
			->accepted()
			->lists('name');
	}

	public function getNamesOfAcceptedCompatibleMusicPluginBanks()
	{
		return $this->compatibleBanks()
			->accepted()
			->lists('name');
	}

	public function getResourceFileTypes()
	{
		return ['sample', 'archive', 'listing-picture'];
	}

	protected function getFilesForStepData()
	{
		$genericFiles = $this->shopItemRevision->resourceFiles()
			->get()
			->map(function ($resourceFile) {
				/** @var \ResourceFile $resourceFile */
				return array_merge($resourceFile->toArray(), ['use_as' => $resourceFile->pivot->file_type]);
			});

		$imageFiles = $this->shopItemRevision->resourceImages()
			->get()
			->map(function ($resourceImage) {
				/** @var \ResourceImage $resourceImage */
				return array_merge($resourceImage->originResourceFile->toArray(), ['use_as' => $resourceImage->pivot->image_type]);
			});

		return array_merge($genericFiles->toArray(), $imageFiles->toArray());
	}

	public function getFiles()
	{
		$genericFiles = $this->shopItemRevision->resourceFiles()
			->get()
			->map(function ($resourceFile) {
				return ['use_as' => $resourceFile->pivot->file_type, 'file' => $resourceFile];
			});

		$imageFiles = $this->shopItemRevision->resourceImages()
			->get()
			->map(function ($resourceImage) {
				/** @var \ResourceImage $resourceImage */
				return ['use_as' => $resourceImage->pivot->image_type, 'file' => $resourceImage->originResourceFile];
			});

		return array_merge($genericFiles->toArray(), $imageFiles->toArray());
	}

	public function getSampleFileAttribute()
	{
		$sampleFile = $this->shopItemRevision->resourceFiles()
			->wherePivot('file_type', '=', 'sample')
			->first();

		return $sampleFile ?: null;
	}

	public function getArchiveFileAttribute()
	{
		$archiveFile = $this->getResourceFileWithType('archive');

		return $archiveFile ?: null;
	}

	public function getListingPictureFileAttribute()
	{
		$pictureFile = $this->getResourceImageWithType('listing-picture');

		return $pictureFile ?: null;
	}

	protected function getResourceFileWithType($fileType)
	{
		$file = $this->shopItemRevision->resourceFiles()
			->wherePivot('file_type', '=', $fileType)
			->first();

		return $file;
	}

	protected function getResourceImageWithType($imageType)
	{
		$image = $this->shopItemRevision->resourceImages()
			->wherePivot('image_type', '=', $imageType)
			->first();

		return $image;
	}
}
