<?php
/**
 * Class UserEmailConfirmation
 *
 * @property int $id
 * @property int $user_id
 * @property User $user
 * @property string $hash
 * @property bool $used
 * @property Carbon\Carbon $created_at
 * @property Carbon\Carbon $updated_at
 */
class UserEmailConfirmation extends Eloquent
{
	protected $table = 'user_email_confirmations';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function scopeUnused($query)
	{
		return $query->where('used', false);
	}
}
