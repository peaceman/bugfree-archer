<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceImageFilesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource_image_files', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('resource_image_id');
			$table->unsignedInteger('resource_file_id');
			$table->string('image_format_identifier');
			$table->timestamps();

			$table->foreign('resource_image_id', 'rif_ri_fk')
				->references('id')->on('resource_images');

			$table->foreign('resource_file_id', 'rif_rf_fk')
				->references('id')->on('resource_files');

			$table->index('image_format_identifier');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_image_files');
	}

}
