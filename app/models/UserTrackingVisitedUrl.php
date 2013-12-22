<?php

class UserTrackingVisitedUrl extends Eloquent
{
	protected $table = 'user_tracking_visited_urls';
	protected $fillable = ['url'];
}
