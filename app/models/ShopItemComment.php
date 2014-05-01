<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ShopItemComment
 *
 * @property int $id
 * @property int $shop_item_id
 * @property string $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ShopItem $shopItem
 */
class ShopItemComment extends Eloquent
{
	use \EDM\ModelTraits\UserTrackingSessionAware;

	protected $table = 'shop_item_comments';
	protected $fillable = ['content'];
	public static $validationRules = [
		'shop_item_id' => ['required', 'exists:shop_items,id'],
		'content' => ['required', 'min:10'],
	];

	public function shopItem()
	{
		return $this->belongsTo('ShopItem');
	}
}
