<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ShopItemRating
 *
 * @property int $id
 * @property int $shop_item_id
 * @property int $rating
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ShopItem $shopItem
 */
class ShopItemRating extends Eloquent
{
	use \EDM\ModelTraits\UserTrackingSessionAware;

	protected $table = 'shop_item_ratings';
	protected $fillable = ['rating'];
	public static $validationRules = [
		'shop_item_id' => ['required', 'exists:shop_items,id'],
		'rating' => ['required', 'numeric', 'between:1,5'],
	];

	public function shopItem()
	{
		return $this->belongsTo('ShopItem');
	}
}
