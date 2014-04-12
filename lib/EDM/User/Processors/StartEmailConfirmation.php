<?php
namespace EDM\User\Processors;

use EDM\ProcessorInterface;
use EDM\User\UserInjection;

class StartEmailConfirmation implements ProcessorInterface
{
	use UserInjection;

	public function process(array $data = null)
	{
		$email = $data['new_email'];

		$emailConfirmation = $this->user->createEmailConfirmation($email);
		$this->user->sendEmailConfirmation(
			$emailConfirmation,
			'emails.user.email-confirmation',
			'mail.user.email_confirmation.subject'
		);
	}
}
