<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

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
}
