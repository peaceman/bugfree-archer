<?php
use Carbon\Carbon;
use EDM\MusicPlugin\Processors\CreateMusicPlugin;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class MusicPlugin
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MusicPlugin extends Eloquent
{
	use EDM\ModelTraits\Reviewable;
	use EDM\ModelTraits\UserTrackingSessionAware;

	protected $table = 'music_plugins';
	protected $fillable = ['name'];
	protected $visible = ['id', 'name', 'banks'];
	public static $validationRules = [
		'name' => ['required', 'min:3', 'max:64', 'unique:music_plugins'],
		'user_tracking_session_id' => ['required', 'exists:user_tracking_sessions'],
	];

	public function banks()
	{
		return $this->hasMany('MusicPluginBank');
	}

	public static function findOrCreateByName($name, $creationAttributes = [])
	{
		$model = static::where('name', $name)->first();
		if (!$model) {
			$creationAttributes['name'] = $name;

			/** @var \EDM\MusicPlugin\Processors\CreateMusicPlugin $createMusicPluginProcessor */
			$createMusicPluginProcessor = App::make(CreateMusicPlugin::class);
			$model = $createMusicPluginProcessor->process($creationAttributes);
		}

		return $model;
	}
}
