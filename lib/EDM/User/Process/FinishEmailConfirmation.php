<?php
namespace EDM\User\Process;

use EDM\ProcessInterface;
use Event;
use Log;

class FinishEmailConfirmation implements ProcessInterface
{
	/**
	 * @param array $data
	 *
	 * @throws \EDM\User\Process\Exception\EmailConfirmation\NonExistingConfirmationHash
	 * @throws \EDM\User\Process\Exception\EmailConfirmation\ExpiredConfirmationHash
	 * @throws \EDM\User\Process\Exception\EmailConfirmation\AlreadyUsedConfirmationHash
	 */
	public function process(array $data = null)
	{
		$confirmationHash = $data['confirmation_hash'];

		$emailConfirmation = $this->searchEmailConfirmationByHash($confirmationHash);
		$this->validateConfirmationState($emailConfirmation);
		$user = $emailConfirmation->user;

		// only change user state to active, if it was in the unconfirmed state
		// to prevent unintentional user state changes
		if ($user->state === \User::STATE_UNCONFIRMED_EMAIL)
			$user->state = \User::STATE_ACTIVE;


		$user->email = $emailConfirmation->email;
		$user->save();

		$this->deactivateUnusedEmailConfirmationsForTheSameAddress($emailConfirmation->email);

		Event::fire(\User::EVENT_EMAIL_CONFIRMATION, $user);
	}

	protected function searchEmailConfirmationByHash($hash)
	{
		$emailConfirmation = \UserEmailConfirmation::where('hash', $hash)->first();
		if (!$emailConfirmation) {
			Log::info(
				'tried to confirm an email address with a non existing confirmation hash',
				['hash' => $hash]
			);

			throw new Exception\EmailConfirmation\NonExistingConfirmationHash();
		}

		return $emailConfirmation;
	}

	protected function validateConfirmationState(\UserEmailConfirmation $confirmation)
	{
		if ($confirmation->state !== \UserEmailConfirmation::STATE_UNUSED) {
			Log::info(
				'tried to confirm an email address with an already used confirmation hash',
				['user_email_confirmation' => $confirmation->toArray()]
			);

			throw new Exception\EmailConfirmation\AlreadyUsedConfirmationHash();
		}

		if ($confirmation->isExpired()) {
			Log::info(
				'tried to confirm an email address with an expired confirmation hash',
				['user_email_confirmation' => $confirmation->toArray()]
			);

			throw new Exception\EmailConfirmation\ExpiredConfirmationHash();
		}
	}

	protected function deactivateUnusedEmailConfirmationsForTheSameAddress($email)
	{
		\UserEmailConfirmation::where('state', \UserEmailConfirmation::STATE_UNUSED)
			->where('email', $email)
			->update(['state' => \UserEmailConfirmation::STATE_DEACTIVATED]);
	}
}
