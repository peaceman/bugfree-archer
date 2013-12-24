<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResourcesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'resource_locations',
			function (Blueprint $table) {
				$table->increments('id');
				$table->string('type');
				$table->enum('state', ['active', 'inactive', 'only_upload']);
				$table->unsignedInteger('priority');
				$table->text('settings')->nullable();
				$table->timestamps();
			}
		);

		Schema::create(
			'resource_files',
			function (Blueprint $table) {
				$table->increments('id');
				$table->boolean('protected')->default(false);
				$table->string('original_name');
				$table->string('mime_type');
				$table->bigInteger('size');
				$table->timestamps();
			}
		);

		Schema::create(
			'resource_file_locations',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('resource_file_id');
				$table->unsignedInteger('resource_location_id');
				$table->string('identifier');
				$table->enum('state', ['new', 'uploading', 'uploaded', 'deleted'])
					->default('new');
				$table->timestamps();

				$table->foreign('resource_file_id', 'rfl_rf_fk')
					->references('id')->on('resource_files')
					->onDelete('cascade');

				$table->foreign('resource_location_id', 'rfl_rl_fk')
					->references('id')->on('resource_locations')
					->onDelete('cascade');
			}
		);

		Schema::create(
			'resource_file_downloads',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('resource_file_location_id');
				$table->unsignedInteger('user_tracking_session_id');
				$table->string('url');
				$table->timestamps();

				$table->foreign('resource_file_location_id', 'rfd_rfl_fk')
					->references('id')->on('resource_file_locations')
					->onDelete('cascade');

				$table->foreign('user_tracking_session_id', 'rfd_uts_fk')
					->references('id')->on('user_tracking_sessions')
					->onDelete('cascade');
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_file_downloads');
		Schema::drop('resource_file_locations');
		Schema::drop('resource_files');
		Schema::drop('resource_locations');
	}

}
