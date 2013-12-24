<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDownloadPriorityToResourceLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resource_locations', function(Blueprint $table)
		{
			$table->dropColumn('priority');
			$table->unsignedInteger('download_order')
				->default(1)
				->after('is_backup');
			$table->unsignedInteger('upload_order')
				->default(1)
				->after('is_backup');
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
			$table->dropColumn('download_order');
			$table->dropColumn('upload_order');
			$table->unsignedInteger('priority')->after('is_backup');
		});
	}

}
