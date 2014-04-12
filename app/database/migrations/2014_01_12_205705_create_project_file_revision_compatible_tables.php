<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFileRevisionCompatibleTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_file_revision_compatible_programs', function(Blueprint $table)
		{
			$table->unsignedInteger('project_file_revision_id');
			$table->unsignedInteger('music_program_id');

			$table->foreign('project_file_revision_id', 'pfrcpr_pfr_fk')
				->references('id')->on('project_file_revisions')
				->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('music_program_id', 'pfrcpr_mp_fk')
				->references('id')->on('music_programs')
				->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('project_file_revision_compatible_plugins', function(Blueprint $table)
		{
			$table->unsignedInteger('project_file_revision_id');
			$table->unsignedInteger('music_plugin_id');

			$table->foreign('project_file_revision_id', 'pfrcpl_pfr_fk')
				->references('id')->on('project_file_revisions')
				->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('music_plugin_id', 'pfrcpl_mp_fk')
				->references('id')->on('music_plugins')
				->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('project_file_revision_compatible_banks', function(Blueprint $table)
		{
			$table->unsignedInteger('project_file_revision_id');
			$table->unsignedInteger('music_bank_id');

			$table->foreign('project_file_revision_id', 'pfrcb_pfr_fk')
				->references('id')->on('project_file_revisions')
				->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('music_bank_id', 'pfrcb_mb_fk')
				->references('id')->on('music_banks')
				->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('project_file_revision_compatible_programs');
		Schema::drop('project_file_revision_compatible_plugins');
		Schema::drop('project_file_revision_compatible_banks');
	}

}
