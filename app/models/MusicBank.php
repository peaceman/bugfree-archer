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

	public function musicPlugin()
	{
		return $this->belongsTo('MusicPlugin');
	}
}
