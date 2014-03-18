<?php
use Carbon\Carbon;

/**
 * Class ShopItemRevision
 *
 * @property int $id
 * @property int $shop_item_id
 * @property int $shop_category_id
 * @property string $price
 * @property string $title
 * @property string $slug
 * @property int $product_revision_id
 * @property string $product_revision_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ShopItem $shopItem
 * @property ShopCategory $shopCategory
 * @property mixed $productRevision
 */
class ShopItemRevision extends Eloquent
{
	use \EDM\ModelTraits\Reviewable;
	use \EDM\ModelTraits\UserTrackingSessionAware;

	protected $table = 'shop_item_revisions';
	protected $fillable = [
		'price', 'title', 'slug',
	];
	public static $validationRules = [
		'shop_item_id' => ['required', 'exists:shop_items'],
		'shop_category_id' => ['required', 'exists:shop_categories'],
		'product_revision_id' => ['required'],
		'product_revision_type' => ['required'],
		'price' => ['required', 'numeric'],
		'title' => ['requierd', 'min:3', 'max:128'],
		'slug' => ['required', 'alpha_dash', 'min:7', 'max:32'],
	];

	public function shopItem()
	{
		return $this->belongsTo('ShopItem');
	}

	public function shopCategory()
	{
		return $this->belongsTo('ShopCategory');
	}

	public function productRevision()
	{
		return $this->morphTo();
	}
}
