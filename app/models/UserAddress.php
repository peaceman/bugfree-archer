<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string $locality
 * @property string $postcode
 * @property string $address_lines
 * @property int $country_id
 *
 * @property User $user
 * @property Country $country
 */
class UserAddress extends Eloquent
{
	protected $table = 'user_addresses';
	protected $fillable = ['locality', 'postcode', 'address_lines', 'country_id'];

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function country()
	{
		return $this->belongsTo('Country');
	}
}
