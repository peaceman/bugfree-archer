<?php
use Carbon\Carbon;

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
	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'inactive';
	const STATE_REPORTED = 'reported';
	const STATE_TEMPORARILY_BLOCKED = 'temporarily_blocked';
	const STATE_PERMANENTLY_BLOCKED = 'permanently_blocked';

	protected $table = 'shop_items';
	protected $fillable = [
		'state',
	];
	public static $validationRules = [
		'owner_id' => ['required', 'exists:users'],
		'state' => ['required', 'alpha_dash'],
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

	public function owner()
	{
		return $this->belongsTo('User');
	}

	public function revisions()
	{
		return $this->hasMany('ShopItemRevision');
	}
}
