<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class MusicGenre
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MusicGenre extends Eloquent
{
	use EDM\ModelTraits\Reviewable;
	use EDM\ModelTraits\UserTrackingSessionAware;

	protected $table = 'music_genres';
	protected $fillable = ['name'];
	protected $visible = ['id', 'name'];

	public static $validationRules = [
		'name' => ['required', 'min:3', 'max:64', 'unique:music_genres'],
		'user_tracking_session_id' => ['required', 'exists:user_tracking_sessions'],
	];
}
