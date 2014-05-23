<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceImagesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resource_images', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('origin_resource_file_id');
			$table->timestamps();

			$table->foreign('origin_resource_file_id', 'ri_rf_fk')
				->references('id')->on('resource_files');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resource_images');
	}

}
