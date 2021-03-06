<?php
use Carbon\Carbon;
use EDM\MusicPluginBank\Processors\CreateMusicPluginBank as CreateMusicPluginBankProcessor;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class MusicPluginBank
 *
 * @property int $id
 * @property string $name
 * @property int $music_plugin_id
 * @property MusicPlugin $musicPlugin
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MusicPluginBank extends Eloquent
{
	use EDM\ModelTraits\Reviewable;
	use EDM\ModelTraits\UserTrackingSessionAware;

	public static $validationRules = [
		'name' => ['required', 'min:3', 'max:64', 'unique:music_plugin_banks'],
		'user_tracking_session_id' => ['required', 'exists:user_tracking_sessions'],
		'music_plugin_id' => ['required', 'exists:music_plugins,id'],
	];
	protected $table = 'music_plugin_banks';
	protected $fillable = ['name', 'music_plugin_id'];
	protected $visible = ['id', 'name', 'music_plugin'];

	public static function findOrCreateByName($name, $creationAttributes = [])
	{
		$model = static::where('name', $name)->first();
		if (!$model) {
			$creationAttributes['name'] = $name;

			if (!is_numeric($creationAttributes['music_plugin_id'])) {
				$musicPlugin = MusicPlugin::where('name', $creationAttributes['music_plugin_id'])->first();
				$creationAttributes['music_plugin_id'] = $musicPlugin->id;
			}

			/** @var \EDM\MusicPluginBank\Processors\CreateMusicPluginBank $createMusicPluginBankProcessor */
			$createMusicPluginBankProcessor = App::make(CreateMusicPluginBankProcessor::class);
			$model = $createMusicPluginBankProcessor->process($creationAttributes);
		}

		return $model;
	}

	public function musicPlugin()
	{
		return $this->belongsTo('MusicPlugin');
	}
}
