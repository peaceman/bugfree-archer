<?php
namespace EDM\ModelTraits;

/**
 * Class UserTrackingSessionAware
 * @package EDM\ModelTraits
 *
 * @property int $user_tracking_session_id
 * @property \UserTrackingSession $userTrackingSession
 */
trait UserTrackingSessionAware
{
	public function userTrackingSession()
	{
		return $this->belongsTo('UserTrackingSession');
	}
}
