<?php
namespace EDM\User;

use Slushie\Middleware\BeforeInterface;
use Slushie\Middleware\AfterInterface;
use Auth;
use Log;

class UserTrackingMiddleware implements BeforeInterface
{
	public function onBefore($request)
	{
		if (!Auth::check())
			return;

		$user = Auth::user();
		$trackingSession = $user->trackingSessions()
			->orderBy('created_at', 'desc')
			->first();

		if (!$trackingSession) {
			Log::warning("couldn't find tracking session for logged in user", ['context' => $user->toArray()]);
			return;
		}

		$trackingSession->visitedUrls()->create(['url' => $request->url()]);
	}
}
