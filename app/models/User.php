<?php

use Carbon\Carbon;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class User
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $real_name
 * @property string $state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property UserProfile $profile
 * @property UserAddress $address
 * @property UserPayoutDetail $payoutDetail
 *
 * @property ShopItem[] $shopItems
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{
	const STATE_UNCONFIRMED_EMAIL = 'unconfirmed_email';
	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'inactive';
	const STATE_TMP_BAN = 'tmp_ban';
	const STATE_PERMA_BAN = 'perma_ban';
	const EVENT_SIGNUP = 'user.signup';
	const EVENT_EMAIL_CONFIRMATION = 'user.email-confirmation';
	const EVENT_LOGIN = 'auth.login';

	public static $validLoginStates = [self::STATE_ACTIVE, self::STATE_TMP_BAN, self::STATE_PERMA_BAN];
	public static $validationRules = [
		'real_name' => ['required', 'max:255'],
		'username' => ['required', 'max:255', 'alpha_dash', 'unique:users'],
		'email' => ['required', 'email', 'max:255', 'unique:users,email'],
		'password' => ['required', 'confirmed'],
	];
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
	protected $fillable = ['email', 'username', 'real_name'];

	public function getEmailValidationRule()
	{
		$emailRule = static::$validationRules['email'];
		$emailRule[3] .= ',' . $this->id;
		return $emailRule;
	}

	public function isAllowedToLogin()
	{
		return in_array($this->state, static::$validLoginStates);
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	public function sendEmailConfirmation(UserEmailConfirmation $emailConfirmation, $mailView, $subject)
	{
		if ($emailConfirmation->state == UserEmailConfirmation::STATE_UNUSED) {
			Log::notice('tried to send confirmation in invalid state', ['context' => $emailConfirmation->toArray()]);
			return false;
		}

		Mail::queue(
			$mailView,
			['user' => $this, 'confirmationHash' => $emailConfirmation->hash],
			function ($msg) use ($emailConfirmation, $subject) {
				$msg->to($emailConfirmation->email)
					->subject(trans($subject));
			}
		);

		return true;
	}

	public function getEmailConfirmationHash()
	{
		return md5($this->id, $this->email);
	}

	public function createEmailConfirmation($emailToConfirm = null)
	{
		if ($emailToConfirm === null) {
			$emailToConfirm = $this->email;
		}

		$emailConfirmation = new UserEmailConfirmation();
		$emailConfirmation->email = $emailToConfirm;
		$emailConfirmation->hash = sha1($this->id . \Illuminate\Support\Str::quickRandom());

		$this->emailConfirmations()->save($emailConfirmation);

		return $emailConfirmation;
	}

	public function emailConfirmations()
	{
		return $this->hasMany('UserEmailConfirmation');
	}

	public function shopItems()
	{
		return $this->hasMany('ShopItem', 'owner_id');
	}

	public function address()
	{
		return $this->hasOne('UserAddress');
	}

	public function payoutDetail()
	{
		return $this->hasOne('UserPayoutDetail');
	}

	public function createTrackingSession()
	{
		$userAgent = UserAgent::firstOrCreate(['value' => Request::server('HTTP_USER_AGENT')]);
		$trackingSession = $this->trackingSessions()->create(
			[
				'ip_address' => Request::server('REMOTE_ADDR'),
				'useragent_id' => $userAgent->id,
			]
		);

		return $trackingSession;
	}

	public function trackingSessions()
	{
		return $this->hasMany('UserTrackingSession');
	}

	public function fetchLastTrackingSession()
	{
		return $this->trackingSessions()
			->orderBy('created_at', 'desc')
			->first();
	}

	public function checkPassword($clearTextPassword)
	{
		return Hash::check($clearTextPassword, $this->password);
	}

	public function getProfile()
	{
		if (!$this->profile()->first()) {
			$profile = UserProfile::createForUser($this);
			return $profile;
		}

		return $this->profile;
	}

	public function profile()
	{
		return $this->hasOne('UserProfile');
	}

	public function getAddress()
	{
		if (!$this->address) {
			$address = new UserAddress();
			$address->user_id = $this->id;

			return $address;
		}

		return $this->address;
	}

	public function getPayoutDetail()
	{
		if (!$this->payoutDetail) {
			$payoutDetail = new UserPayoutDetail();
			$payoutDetail->user_id = $this->id;

			return $payoutDetail;
		}

		return $this->payoutDetail;
	}

	public function getQualifiesAsVendor()
	{
		return (bool)$this->address && $this->payoutDetail;
	}

	public function getAmountOfSales()
	{
		$query = $this->generateGetSalesQuery();

		return $query->count();
	}

	/**
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function generateGetSalesQuery()
	{
		$query = ShopOrder::withPaymentState(ShopOrder::PAYMENT_STATE_DONE)
			->select('shop_orders.*')
			->join('shop_item_revisions', 'shop_orders.shop_item_revision_id', '=', 'shop_item_revisions.id')
			->join('shop_items', 'shop_item_revisions.shop_item_id', '=', 'shop_items.id')
			->where('shop_items.owner_id', '=', $this->id);
		return $query;
	}

	public function getAmountOfSalesOfToday()
	{
		$query = $this->generateGetSalesQuery();
		$query->whereBetween('shop_orders.created_at', [Carbon::today(), Carbon::tomorrow()]);

		return $query->count();
	}

	public function getAmountOfSalesOfThisWeek()
	{
		$query = $this->generateGetSalesQuery();
		$query->whereBetween('shop_orders.created_at', [
			Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
		]);

		return $query->count();
	}

	public function getRevenue()
	{
		return 42.23;
	}

	public function hasAlreadyBoughtShopItem(ShopItem $shopItem)
	{
		$query = ShopOrder::query()
			->join('user_tracking_sessions AS uts', 'shop_orders.user_tracking_session_id', '=', 'uts.id')
			->join('shop_item_revisions as sir', 'shop_orders.shop_item_revision_id', '=', 'sir.id')
			->where('sir.shop_item_id', '=', $shopItem->id)
			->where('uts.user_id', '=', $this->id)
			->groupBy('sir.shop_item_id');

		return (int)$query->count() !== 0;
	}
}
