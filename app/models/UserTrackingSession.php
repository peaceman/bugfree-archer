<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;

/**
 * Class UserTrackingSession
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property int $useragent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property UserAgent $userAgent
 * @property User $user
 */
class UserTrackingSession extends Eloquent
{
	protected $table = 'user_tracking_sessions';
	protected $fillable = ['ip_address', 'usergent_id'];

	public function userAgent()
	{
		return $this->belongsTo('UserAgent', 'useragent_id');
	}

	public function visitedUrls()
	{
		return $this->hasMany('UserTrackingVisitedUrl', 'session_id');
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
