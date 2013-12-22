<?php

use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;

/**
 * Class User
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $real_name
 * @property string $state
 * @property Carbon\Carbon $created_at
 * @property Carbon\Carbon $updated_at
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{
	const STATE_UNCONFIRMED_EMAIL = 'unconfirmed_email';
	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'inactive';
	const STATE_TMP_BAN = 'tmp_ban';
	const STATE_PERMA_BAN = 'perma_ban';
	const EVENT_SIGNUP = 'user.signup';
	const EVENT_EMAIL_CONFIRMATION = 'user.email-confirmation';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
	protected $fillable = ['email', 'username', 'real_name'];

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	public function sendEmailConfirmation(UserEmailConfirmation $emailConfirmation)
	{
    	   if ($emailConfirmation->used) {
        	   Log::notice('tried to send already used confirmation', ['context' => $emailConfirmation->toArray()]);
        	   return false;
    	   }
    	   
    	   $user = $this;
    	   Mail::queue(
    	       'emails.user.signup',
    	       ['user' => $this, 'confirmationHash' => $emailConfirmation->hash],
    	       function ($msg) use ($user) {
        	       $msg->to($user->email)
        	           ->subject(trans('mail.user.sign_up_confirmation.subject'));
    	       }
    	   );
    	   
    	   return true;
	}

	public function getEmailConfirmationHash()
	{
		return md5($this->id, $this->email);
	}

	public function createEmailConfirmation()
	{
		$emailConfirmation = new UserEmailConfirmation();
		$emailConfirmation->hash = sha1($this->id . \Illuminate\Support\Str::quickRandom());

		$this->emailConfirmations()->save($emailConfirmation);

		return $emailConfirmation;
	}

	public function emailConfirmations()
	{
		return $this->hasMany('UserEmailConfirmation');
	}
}
