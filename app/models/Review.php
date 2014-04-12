<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Review
 *
 * @property int $id
 * @property int $reviewer_id
 * @property User $reviewer
 * @property string $state
 * @property int $reviewee_id
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
	const REVIEWEE_MUSIC_PROGRAM = 'MusicProgram';
	const REVIEWEE_MUSIC_PLUGIN = 'MusicPlugin';

	protected $table = 'reviews';
	public static $validationRules = [
		'reviewer_id' => ['required', 'exists:users'],
		'state' => ['required', 'alpha_dash'],
		'reviewee_id' => ['required'],
		'reviewee_type' => ['required'],
		'result' => ['digits:1', 'in,0,1'],
	];

	public function __construct()
	{
		$this->state = static::STATE_WAITING;
	}

	public function reviewer()
	{
		return $this->belongsTo('User');
	}

	public function reviewee()
	{
		return $this->morphTo();
	}
}
