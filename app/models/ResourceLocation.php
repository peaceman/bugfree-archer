<?php
use Carbon\Carbon;

/**
 * Class ResourceLocation
 *
 * @property int $id
 * @property string $type
 * @property string $state
 * @property bool $is_backup
 * @property int $priority
 * @property array $settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ResourceLocation extends Eloquent
{
	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'inactive';
	const STATE_ONLY_UPLOAD = 'only_upload';
	protected $table = 'resource_locations';

	public function resourceFileLocations()
	{
		return $this->hasMany('ResourceFileLocation');
	}

	public function qualifiesForInstantTransport()
	{
		return $this->priority === Config::get('storage.instant_store_priority');
	}

	public function isBackupLocation()
	{
		return $this->is_backup;
	}
}
