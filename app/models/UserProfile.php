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

	public function avatar()
	{
		return $this->belongsTo('ResourceFile', 'picture_file_id');
	}
}
