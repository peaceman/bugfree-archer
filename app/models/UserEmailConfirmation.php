<?php
/**
 * Class UserEmailConfirmation
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property User $user
 * @property string $hash
 * @property string $state
 * @property Carbon\Carbon $created_at
 * @property Carbon\Carbon $updated_at
 */
class UserEmailConfirmation extends Eloquent
{
	const STATE_UNUSED = 'unused';
	const STATE_USED = 'used';
	const STATE_DEACTIVATED = 'deactivated';
	const STATE_EXPIRED = 'expired';
	public static $defaultExpiryMinutes = 15;
	public static $stateContext = [
		'unused' => 'info',
		'used' => 'success',
		'deactivated' => 'danger',
		'expired' => 'warning',
	];
	protected $table = 'user_email_confirmations';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function scopeUnused($query)
	{
		return $query->where('used', false);
	}

	public function isExpired()
	{
		$expiryInterval = Config::get(
			'app.email_confirmation_expiry_interval_in_minutes',
			static::$defaultExpiryMinutes
		);
		$expiredAt = $this->created_at->copy()->addMinutes($expiryInterval);

		return $expiredAt->isPast();
	}

	public function stateContext()
	{
		return static::$stateContext[$this->state];
	}
}
