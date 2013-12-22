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
		$emailConfirmation = $user->createEmailConfirmation();

		Mail::queue(
			'emails.user.signup',
			['user' => $user, 'confirmationHash' => $emailConfirmation->hash],
			function ($message) use ($user) {
				$message->to($user->email)
				->subject(trans('mail.user.sign_up_confirmation.subject'));
			}
		);
	}
}
