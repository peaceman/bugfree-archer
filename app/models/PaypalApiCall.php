<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class PaypalApiCall
 *
 * @property int $id
 * @property int $shop_order_id
 * @property PayPal\Core\PPMessage $request
 * @property PayPal\Core\PPMessage $response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ShopOrder $shopOrder
 */
class PaypalApiCall extends Eloquent
{
	protected $table = 'paypal_api_calls';

	public function shopOrder()
	{
		return $this->belongsTo('ShopOrder');
	}

	public function setRequestAttribute($value)
	{
		$this->attributes['request'] = serialize($value);
	}

	public function getRequestAttribute($value)
	{
		return unserialize($value);
	}

	public function setResponseAttribute($value)
	{
		$this->attributes['response'] = serialize($value);
	}

	public function getResponseAttribute($value)
	{
		return unserialize($value);
	}
}
