<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserPayoutDetail
 *
 * @property int $id
 * @property int $user_id
 * @property string $paypal_email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 */
class UserPayoutDetail extends Eloquent
{
	use \EDM\ModelTraits\UserTrackingSessionAware;

	public static $validationRules = [
		'paypal_email' => ['required', 'email'],
	];
	protected $table = 'user_payout_details';

	public function user()
	{
		return $this->belongsTo('User');
	}
}
