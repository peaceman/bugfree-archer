<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

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
 * @property ProductRevisionInterface $productRevision
 * @property ShopOrder[] $shopOrders
 * @property ResourceImage[] $resourceImages
 * @property ResourceFile[] $resourceFiles
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
		'shop_item_id' => ['required', 'exists:shop_items,id'],
		'shop_category_id' => ['required', 'exists:shop_categories,id'],
		'product_revision_id' => ['required'],
		'product_revision_type' => ['required'],
		'price' => ['required', 'numeric'],
		'title' => ['required', 'min:3', 'max:128'],
		'slug' => ['required', 'alpha_dash', 'min:7', 'max:32'],
	];

	public function finishedReviewWithResult($accepted)
	{
		$processor = $accepted
			? \App::make(\EDM\ShopItemRevision\Processors\AcceptShopItemRevision::class)
			: \App::make(\EDM\ShopItemRevision\Processors\RejectShopItemRevision::class);

		$processor->process([
			'shop_item_revision' => $this,
		]);
	}

	public function getNameForReview()
	{
		return $this->title;
	}

	protected static function boot()
	{
		parent::boot();

		static::deleting(function($shopItemRevision) {
			if ($shopItemRevision->productRevision) {
				$shopItemRevision->productRevision->delete();
			}

			if ($shopItemRevision->review) {
				$shopItemRevision->review->delete();
			}
		});
	}

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

	public function shopOrders()
	{
		return $this->hasMany('ShopOrder');
	}

	public function resourceImages()
	{
		return $this->belongsToMany('ResourceImage', 'shop_item_revision_images')
			->withPivot('image_type')
			->withTimestamps();
	}

	public function resourceFiles()
	{
		return $this->belongsToMany('ResourceFile', 'shop_item_revision_files')
			->withPivot('file_type')
			->withTimestamps();
	}

	public function getMetaData()
	{
		$metaData = $this->productRevision->getMetaData();

		$toReturn = [];
		foreach ($metaData as $name => $value) {
			$toReturn[] = trans('shop.meta_data.' . $name) . ': ' . $value;
		}

		return $toReturn;
	}
}
