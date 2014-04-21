<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveFileInfoFromProjectFileRevisionsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_file_revisions', function (Blueprint $table) {
			$table->dropForeign('pfr_rf_afi_fk');
			$table->dropForeign('pfr_rf_sfi_fk');
			$table->dropColumn('sample_file_id', 'archive_file_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		throw new \LogicException('there is no return');
	}

}
