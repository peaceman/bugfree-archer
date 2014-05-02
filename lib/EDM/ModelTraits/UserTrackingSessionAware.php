<?php
namespace EDM\ModelTraits;

/**
 * Class UserTrackingSessionAware
 * @package EDM\ModelTraits
 *
 * @property int $user_tracking_session_id
 * @property \UserTrackingSession $userTrackingSession
 * @property \User $submitter
 *
 * @method static \Illuminate\Database\Eloquent\Builder onlyFromUser(\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder asUser(\User $user)
 */
trait UserTrackingSessionAware
{
	public function userTrackingSession()
	{
		return $this->belongsTo('UserTrackingSession');
	}

	public function scopeAsUser($query, \User $user)
	{
		return $query
			->orWhereHas('userTrackingSession', function ($q) use ($user) {
				$q->where('user_id', '=', $user->id);
			});
	}

	public function scopeOnlyFromUser($query, \User $user)
	{
		return $query->whereHas('userTrackingSession', function ($q) use ($user) {
			$q->where('user_id', '=', $user->id);
		});
	}

	public function getSubmitter()
	{
		if (!$this->userTrackingSession) {
			return null;
		}

		return $this->userTrackingSession->user;
	}

	public function getSubmitterAttribute()
	{
		return $this->getSubmitter();
	}
}
