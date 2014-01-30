<?php
use Carbon\Carbon;

/**
 * Class Review
 *
 * @property int $id
 * @property int $reviewer_id
 * @property User $reviewer
 * @property string $state
 * @property string $reviewee_type
 * @property string $result
 * @property string $result_reasoning
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Review extends Eloquent
{
	const STATE_WAITING = 'waiting';
	const STATE_IN_PROGRESS = 'in_progress';
	const STATE_FINISHED = 'finished';

	const REVIEWEE_MUSIC_GENRE = 'MusicGenre';

	protected $table = 'reviews';

	public function __construct()
	{
		$this->state = static::STATE_WAITING;
	}

	public function reviewer()
	{
		return $this->belongsTo('User');
	}
}
