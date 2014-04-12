<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class UserTrackingVisitedUrl extends Eloquent
{
	protected $table = 'user_tracking_visited_urls';
	protected $fillable = ['url'];
	protected $touches = ['trackingSession'];

	public function trackingSession()
	{
		return $this->belongsTo('UserTrackingSession', 'session_id');
	}
}
