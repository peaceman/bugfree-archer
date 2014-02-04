<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDataToResourceFile extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_files', function(Blueprint $table)
		{
			$table->unsignedInteger('user_tracking_session_id')
				->nullable()
				->default(null)
				->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resource_files', function(Blueprint $table)
		{
			$table->dropColumn('user_tracking_session_id');
		});
	}

}