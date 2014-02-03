<?php
use Carbon\Carbon;

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
}
