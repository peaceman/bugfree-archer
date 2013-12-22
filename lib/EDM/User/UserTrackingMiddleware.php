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
	}
}
