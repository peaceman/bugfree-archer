<?php

class UserTrackingSession extends Eloquent
{
	protected $table = 'user_tracking_sessions';
	protected $fillable = ['ip_address', 'usergent_id'];
}
