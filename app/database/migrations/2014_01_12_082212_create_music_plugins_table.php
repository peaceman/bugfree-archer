<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicPluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('music_plugins', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('review_id')->nullable();
			$table->unsignedInteger('user_tracking_session_id')->nullable();
			$table->unsignedInteger('music_plugin_id');
			$table->string('name', 64);
			$table->timestamps();

			$table->foreign('music_plugin_id')
				->references('id')->on('music_plugins')
				->onUpdate('cascade')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('music_plugins');
	}

}
