<?php
use Carbon\Carbon;
use EDM\ModelTraits;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ShopItem
 *
 * @property int $id
 * @property int $owner_id
 * @property User $owner
 * @property string $state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ShopItemRevision[] $revisions
 * @property ShopItemRevision $activeRevision
 * @property ShopItemComment[] $comments
 * @property ShopItemRating[] $ratings
 *
 * @method static Illuminate\Database\Eloquent\Builder withState(string $state)
 */
class ShopItem extends Eloquent
{
	use ModelTraits\OwnerAware;
	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'inactive';
	const STATE_REPORTED = 'reported';
	const STATE_TEMPORARILY_BLOCKED = 'temporarily_blocked';
	const STATE_PERMANENTLY_BLOCKED = 'permanently_blocked';
	public static $validationRules = [
		'owner_id' => ['required', 'exists:users'],
		'state' => ['required', 'alpha_dash'],
	];
	protected $table = 'shop_items';
	protected $fillable = [
		'state',
	];

	public function __construct(array $attributes = [])
	{
		$attributes = array_merge(
			[
				'state' => static::STATE_INACTIVE,
			],
			$attributes
		);

		parent::__construct($attributes);
	}

	protected static function boot()
	{
		parent::boot();

		static::deleting(function ($shopItem) {
			foreach ($shopItem->revisions as $revision) {
				$revision->delete();
			}
		});
	}

	public function generateStepData()
	{
		$latestRevision = $this->latestRevision();

		$stepData = [
			'general' => [
				'inputData' => [
					'title' => $latestRevision->title,
					'price' => (int)$latestRevision->price,
					'shop_category_id' => $latestRevision->shopCategory->id
				],
				'state' => 'done',
			],
		];

		$stepData = array_merge($stepData, $latestRevision->productRevision->generateStepData());

		return $stepData;
	}

	/**
	 * @return \ShopItemRevision|null
	 */
	public function latestAcceptedRevision()
	{
		$latestRevision = $this->revisions()
			->accepted()
			->latest()
			->first();

		return $latestRevision;
	}

	/**
	 * @return \ShopItemRevision|null
	 */
	public function latestRevision()
	{
		$latestRevision = $this->revisions()->latest()->first();
		return $latestRevision;
	}

	public function revisions()
	{
		return $this->hasMany('ShopItemRevision');
	}

	public function activeRevision()
	{
		return $this->belongsTo('ShopItemRevision', 'active_revision_id');
	}

	public function canUpdateLatestRevision()
	{
		$latestRevision = $this->latestRevision();
		return $latestRevision->review->state === Review::STATE_WAITING;
	}

	public function comments()
	{
		return $this->hasMany('ShopItemComment');
	}

	public function ratings()
	{
		return $this->hasMany('ShopItemRating');
	}

	public function getAverageRatingAttribute()
	{
		// todo cache
		return $this->fetchAverageRating();
	}

	public function fetchAverageRating()
	{
		return $this->ratings()
			->avg('rating');
	}

	public function getRoundedAverageRatingAttribute()
	{
		return round($this->getAverageRatingAttribute());
	}

	public function scopeWithState(Illuminate\Database\Eloquent\Builder $query, $state)
	{
		return $query->where('state', '=', $state);
	}

	/**
	 * @param string $slug
	 * @return ShopItem
	 */
	public static function fetchActiveShopItemWithSlug($slug)
	{
		$shopItem = ShopItem::withState(ShopItem::STATE_ACTIVE)
			->whereHas('activeRevision', function ($query) use ($slug) {
				$query->where('slug', '=', $slug);
			})
			->firstOrFail();

		return $shopItem;
	}

	/**
	 * @param int $revisionId
	 * @return ShopItem
	 */
	public static function fetchActiveShopItemWithRevisionId($revisionId)
	{
		$shopItem = ShopItem::withState(ShopItem::STATE_ACTIVE)
			->whereHas('activeRevision', function ($query) use ($revisionId) {
				$query->where('id', '=', $revisionId);
			})
			->firstOrFail();

		return $shopItem;
	}

	public function isInABuyableState()
	{
		return $this->state === static::STATE_ACTIVE;
	}

	public function isSeller(User $user)
	{
		return (int)$this->owner->id === (int)$user->id;
	}
}
