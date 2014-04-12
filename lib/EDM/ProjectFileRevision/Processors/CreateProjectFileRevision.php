<?php
namespace EDM\ProjectFileRevision\Processors;

use EDM\ProcessorInterface;
use EDM\ProjectFileRevision\ValidatorBag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MusicGenre;
use EDM\MusicGenre\Processors\CreateMusicGenre as CreateMusicGenreProcessor;
use ProjectFileRevision;
use ResourceFile;
use App;

class CreateProjectFileRevision implements ProcessorInterface
{
	/**
	 * @var \EDM\ProjectFileRevision\ValidatorBag
	 */
	protected $validatorBag;

	public function __construct(ValidatorBag $validatorBag)
	{
		$this->validatorBag = $validatorBag;
	}

	public function process(array $data = null)
	{
		$inputData = array_get($data, 'input_data', []);

		$projectFileRevision = new ProjectFileRevision([
			'bpm' => array_get($inputData, 'project-file.bpm'),
			'description' => array_get($inputData, 'project-file.description')
		]);

		$this->setMusicGenreOnProjectFileRevision($inputData, $projectFileRevision);
		$this->setResourceFilesOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnProjectFileRevision($this->validatorBag->preSave, $projectFileRevision);

		$projectFileRevision->save();

		$this->setCompatibleSoftwareOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnProjectFileRevision($this->validatorBag->postSave, $projectFileRevision);

		return $projectFileRevision;
	}

	protected function setMusicGenreOnProjectFileRevision(array $inputData, ProjectFileRevision $projectFileRevision)
	{
		$idOrName = array_get($inputData, 'project-file.music_genre_id');
		$musicGenre = is_numeric($idOrName) ? MusicGenre::find($idOrName) : MusicGenre::where('name', $idOrName)->first();

		if (!$musicGenre && !is_numeric($idOrName)) {
			/** @var CreateMusicGenreProcessor $createMusicGenreProcessor */
			$createMusicGenreProcessor = App::make(CreateMusicGenreProcessor::class);
			$musicGenre = $createMusicGenreProcessor->process([
				'name' => $idOrName
			]);
		}

		$projectFileRevision->musicGenre()->associate($musicGenre);
	}

	protected function setResourceFilesOnProjectFileRevision(array $inputData, ProjectFileRevision $projectFileRevision)
	{
		$resourceFileInfos = Collection::make(array_get($inputData, 'upload-file.selectedFiles'))
			->lists('use_as', 'id');

		$sampleFileId = array_search('sample', $resourceFileInfos);
		$sampleFile = ResourceFile::findOrFail($sampleFileId);
		$projectFileRevision->sampleFile()->associate($sampleFile);

		$archiveFileId = array_search('archive', $resourceFileInfos);
		$archiveFile = ResourceFile::findOrFail($archiveFileId);
		$projectFileRevision->archiveFile()->associate($archiveFile);
	}

	/**
	 * @param \EDM\ProjectFileRevision\ValidatorInterface[] $validators
	 * @param ProjectFileRevision $projectFileRevision
	 */
	protected function executeValidatorsOnProjectFileRevision(array $validators, ProjectFileRevision $projectFileRevision)
	{
		foreach ($validators as $validator) {
			$validator->validate($projectFileRevision);
		}
	}

	protected function setCompatibleSoftwareOnProjectFileRevision(array $inputData, ProjectFileRevision $projectFileRevision)
	{
		$compatibleSoftwareTypes = [
			['relation_name' => 'banks', 'model_class' => \MusicPluginBank::class],
			['relation_name' => 'plugins', 'model_class' => \MusicPlugin::class],
			['relation_name' => 'programs', 'model_class' => \MusicProgram::class],
		];

		foreach ($compatibleSoftwareTypes as $compatibleSoftwareType) {
			$modelClassShortName = (new \ReflectionClass($compatibleSoftwareType['model_class']))
				->getShortName();
			$modelClassShortName = Str::snake($modelClassShortName);

			$this->setCompatibleSoftwareTypeOnProjectFileRevision(
				$compatibleSoftwareType,
				array_get($inputData, "project-file.{$modelClassShortName}s"),
				$projectFileRevision
			);
		}
	}

	protected function setCompatibleSoftwareTypeOnProjectFileRevision(array $softwareType, array $softwareData, ProjectFileRevision $projectFileRevision)
	{
		$ids = [];

		$modelReflectionClass = new \ReflectionClass($softwareType['model_class']);
		$findOrCreateByNameMethod = $modelReflectionClass->getMethod('findOrCreateByName');
		$findOrFailMethod = $modelReflectionClass->getMethod('findOrFail');

		foreach ($softwareData as $software) {
			$model = is_numeric($software['id_or_name'])
				? $findOrFailMethod->invoke(null, $software['id_or_name'])
				: $findOrCreateByNameMethod->invoke(null, $software['id_or_name'], $software['additional_data']);

			$ids[] = $model->id;
		}

		$relationName = 'compatible' . ucfirst($softwareType['relation_name']);
		$projectFileRevision->$relationName()->sync($ids);
	}
}
