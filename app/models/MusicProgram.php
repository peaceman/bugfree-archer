<?php
use Carbon\Carbon;

/**
 * Class MusicProgram
 *
 * @property int $id
 * @property string $name
 * @proeprty Carbon $created_at
 * @proeprty Carbon $updated_at
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
}
