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
	}

	/**
	 * @param \User $user
	 */
	public function onUserSignUp($user)
	{
		Mail::queue(
			'emails.user.signup',
			['user' => $user, 'confirmationHash' => $user->getEmailConfirmationHash()],
			function ($message) use ($user) {
				$message->to($user->email)->subject('Your registration at EDM Market');
			}
		);
	}
}
