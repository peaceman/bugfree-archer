<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string $website
 * @property string $about
 * @property int $avatar_resource_image_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 * @property ResourceImage $avatar
 */
class UserProfile extends Eloquent
{
	protected $table = 'user_profiles';
	protected $fillable = ['website', 'about'];

	public function __construct(array $attributes = [])
	{
		$this->website = '';
		$this->about = '';

		parent::__construct($attributes);
	}

	public static function createForUser(User $user)
	{
		$profile = new static();
		$profile->user()->associate($user);
		$profile->save();
		return $profile;
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function avatar()
	{
		return $this->belongsTo('ResourceImage', 'avatar_resource_image_id');
	}

	public function hasAvatar()
	{
		return $this->avatar_resource_image_id !== null;
	}
}
