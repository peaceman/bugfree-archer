<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertAvatarsToResourceImages extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_profiles', function (Blueprint $table) {
			$table->dropForeign('up_rf_fk');
			$table->dropColumn('picture_file_id');

			$table->unsignedInteger('avatar_resource_image_id')
				->nullable()
				->after('about');

			$table->foreign('avatar_resource_image_id', 'up_ri_fk')
				->references('id')->on('resource_images');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_profiles', function (Blueprint $table) {
			$table->dropForeign('up_ri_fk');
			$table->dropColumn('avatar_resource_image_id');

			$table->unsignedInteger('picture_file_id')
				->nullable()
				->after('about');

			$table->foreign('picture_file_id', 'up_rf_fk')
				->references('id')->on('resource_files');
		});
	}

}
