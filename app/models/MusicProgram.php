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
}
