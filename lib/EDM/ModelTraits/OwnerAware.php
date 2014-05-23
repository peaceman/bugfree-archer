<?php
namespace EDM\ModelTraits;

/**
 * Class OwnerAware
 * @package EDM\ModelTraits
 *
 * @property int $owner_id
 * @property \User $owner
 *
 * @method static \Illuminate\Database\Eloquent\Builder onlyFromOwner(\User $user)
 */
trait OwnerAware
{
	public function owner()
	{
		return $this->belongsTo('User');
	}

	public function scopeOnlyFromOwner($query, \User $user)
	{
		return $query->where('owner_id', $user->id);
	}
}
