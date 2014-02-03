<?php
use Carbon\Carbon;

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

	protected $table = 'music_plugin_banks';
	protected $fillable = ['name', 'music_plugin_id'];
	protected $visible = ['id', 'name', 'music_plugin'];
	public static $validationRules = [
		'name' => ['required', 'min:3', 'max:64', 'unique:music_plugin_banks'],
		'user_tracking_session_id' => ['required', 'exists:user_tracking_sessions'],
		'music_plugin_id' => ['required', 'exists:music_plugins,id'],
	];

	public function musicPlugin()
	{
		return $this->belongsTo('MusicPlugin');
	}
}
