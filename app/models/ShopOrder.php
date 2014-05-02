<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ShopOrder
 *
 * @property int $id
 * @property int $shop_item_revision_id
 * @property string $payment_state
 * @property string $paypal_pay_key
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ShopItemRevision $shopItemRevision
 * @property PaypalApiCall[] $paypalApiCalls
 * @property \User $buyer
 *
 * @method static Illuminate\Database\Eloquent\Builder withPaymentState(string $state)
 */
class ShopOrder extends Eloquent
{
	use \EDM\ModelTraits\UserTrackingSessionAware;

	const PAYMENT_STATE_OPEN = 'open';
	const PAYMENT_STATE_DONE = 'done';

	public static $validationRules = [
		'payment_state' => ['required', 'in:open,done'],
	];

	protected $table = 'shop_orders';
	protected $fillable = ['payment_state'];

	public function __construct(array $attributes = [])
	{
		parent::__construct(array_merge(
			[
				'payment_state' => static::PAYMENT_STATE_OPEN,
			],
			$attributes
		));
	}

	public function shopItemRevision()
	{
		return $this->belongsTo('ShopItemRevision');
	}

	public function paypalApiCalls()
	{
		return $this->hasMany('PaypalApiCall');
	}

	public function scopeWithPaymentState(Illuminate\Database\Eloquent\Builder $query, $state)
	{
		return $query->where('payment_state', '=', $state);
	}

	public function getBuyerAttribute()
	{
		return $this->getSubmitter();
	}
}
