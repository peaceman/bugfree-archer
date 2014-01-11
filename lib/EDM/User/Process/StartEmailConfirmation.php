<?php
namespace EDM\User\Process;

class StartEmailConfirmation extends AbstractUserProcess
{
	public function process(array $data)
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
