<?php
use Carbon\Carbon;

/**
 * Class ResourceFileDownload
 *
 * @property int $id
 * @property int $resource_file_location_id
 * @property ResourceFileLocation $resourceFileLocation
 * @property int $user_tracking_session_id
 * @property UserTrackingSession $userTrackingSession
 * @property string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ResourceFileDownload extends Eloquent
{
	protected $table = 'resource_file_downloads';

	public function resourceFileLocation()
	{
		$this->belongsTo('ResourceFileLocation');
	}

	public function userTrackingSession()
	{
		$this->belongsTo('UserTrackingSession');
	}
}
