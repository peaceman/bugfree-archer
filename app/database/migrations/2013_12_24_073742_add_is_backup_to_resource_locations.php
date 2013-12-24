<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsBackupToResourceLocations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_locations', function(Blueprint $table)
		{
			$table->boolean('is_backup')->default(false)
				->after('state');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_locations', function(Blueprint $table)
		{
			$table->dropColumn('is_backup');
		});
	}

}
