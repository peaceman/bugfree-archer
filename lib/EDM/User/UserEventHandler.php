<?php
namespace EDM\User;

use Mail;

class UserEventHandler
{
	/**
	 * @param \Illuminate\Events\Dispatcher $events
	 */
	public function subscribe($events)
	{
		$events->listen(\User::EVENT_SIGNUP, __CLASS__ . '@onUserSignUp');
		$events->listen(\User::EVENT_LOGIN, __CLASS__ . '@onUserLogin');
	}

	/**
	 * @param \User $user
	 */
	public function onUserLogin($user)
	{
		$user->createTrackingSession();
	}

	/**
	 * @param \User $user
	 */
	public function onUserSignUp($user)
	{
    	   $emailConfirmation = $user->createEmailConfirmation();
    	   $user->sendEmailConfirmation($emailConfirmation);
	}
}
