<?php
use Carbon\Carbon;

/**
 * Class UserProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string $website
 * @property string $about
 * @property int $picture_file_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 * @property ResourceFile $avatar
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
		return $this->belongsTo('ResourceFile', 'picture_file_id');
	}

	public function hasAvatar()
	{
		return $this->picture_file_id !== null;
	}
}
