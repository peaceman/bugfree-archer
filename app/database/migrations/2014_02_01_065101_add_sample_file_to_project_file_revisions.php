<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSampleFileToProjectFileRevisions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_file_revisions', function (Blueprint $table) {
			$table->dropForeign('project_file_revisions_resource_file_id_foreign');
		});

		Schema::table('project_file_revisions', function(Blueprint $table) {
			$table->renameColumn('resource_file_id', 'archive_file_id');
			$table->unsignedInteger('sample_file_id')->after('music_genre_id');
		});

		Schema::table('project_file_revisions', function (Blueprint $table) {
			$table->foreign('archive_file_id', 'pfr_rf_afi_fk')
				->references('id')->on('resource_files')
				->onUpdate('cascade')
				->onDelete('restrict');

			$table->foreign('sample_file_id', 'pfr_rf_sfi_fk')
				->references('id')->on('resource_files')
				->onUpdate('cascade')
				->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_file_revisions', function(Blueprint $table) {
			$table->dropForeign('pfr_rf_arfi_fk');
			$table->dropForeign('pfr_rf_srfi_fk');

			$table->dropColumn('sample_resource_file_id');
		});

		Schema::table('project_file_revisions', function (Blueprint $table) {
			$table->renameColumn('archive_resource_file_id', 'resource_file_id');
		});

		Schema::table('project_file_revisions', function (Blueprint $table) {
			$table->foreign('resource_file_id')
				->references('id')->on('resource_files')
				->onUpdate('cascade')
				->onDelete('restrict');
		});
	}

}