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

		static::deleting(function($shopItem) {
			foreach ($shopItem->revisions as $revision) {
				$revision->delete();
			}
		});
	}

	public function revisions()
	{
		return $this->hasMany('ShopItemRevision');
	}

	public function latestRevision()
	{
		$latestRevision = $this->revisions()->latest()->first();
		return $latestRevision;
	}
}
