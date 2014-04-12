<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use EDM\MusicProgram\Processors\CreateMusicProgram as CreateMusicProgramProcessor;

/**
 * Class MusicProgram
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MusicProgram extends Eloquent
{
	use EDM\ModelTraits\Reviewable;
	use EDM\ModelTraits\UserTrackingSessionAware;

	protected $table = 'music_programs';
	protected $fillable = ['name'];
	protected $visible = ['id', 'name'];
	public static $validationRules = [
		'name' => ['required', 'min:3', 'max:64', 'unique:music_programs'],
		'user_tracking_session_id' => ['required', 'exists:user_tracking_sessions'],
	];

	public static function findOrCreateByName($name, $creationAttributes = [])
	{
		$model = static::where('name', $name)->first();
		if (!$model) {
			$creationAttributes['name'] = $name;

			/** @var \EDM\MusicProgram\Processors\CreateMusicProgram $createMusicProgramProcessor */
			$createMusicProgramProcessor = App::make(CreateMusicProgramProcessor::class);
			$model = $createMusicProgramProcessor->process($creationAttributes);
		}

		return $model;
	}
}
