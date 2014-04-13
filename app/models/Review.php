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
	public static $validationRules = [
		'reviewer_id' => ['required', 'exists:users'],
		'state' => ['required', 'alpha_dash'],
		'reviewee_id' => ['required'],
		'reviewee_type' => ['required'],
		'result' => ['digits:1', 'in,0,1'],
	];
	protected $table = 'reviews';

	public function __construct()
	{
		$this->state = static::STATE_WAITING;
	}

	public static function amountOfWaitingReviews()
	{
		$amount = static::query()
			->where('state', static::STATE_WAITING)
			->count();

		return $amount;
	}

	public static function fetchPaginatedReviewsWithState($state, $perPage = 10)
	{
		/** @var \Illuminate\Database\Query\Builder $query */
		$query = static::withState($state)
			->orderBy('created_at', 'desc');
		$pageQueryParamName = 'p' . ucfirst($state);

		$paginationEnv = new \Illuminate\Pagination\Environment(
			App::make('request'),
			App::make('view'),
			App::make('translator'),
			$pageQueryParamName
		);
		$paginationEnv->setViewName(App::make('config')['view.pagination']);
		App::refresh('request', $paginationEnv, 'setRequest');

		\DB::connection()->setPaginator($paginationEnv);
		$result = $query->paginate($perPage);
		\DB::connection()->setPaginator(App::make('paginator'));

		$result->appends(Input::except($pageQueryParamName));
		return $result;
	}

	public function reviewer()
	{
		return $this->belongsTo('User');
	}

	public function reviewee()
	{
		return $this->morphTo();
	}

	public function scopeWithState($query, $state)
	{
		return $query->where('state', $state);
	}

	public function getReviewedAtAttribute()
	{
		return $this->state === static::STATE_FINISHED
			? $this->updated_at
			: null;
	}
}
