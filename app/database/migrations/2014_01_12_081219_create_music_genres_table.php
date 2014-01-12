<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicGenresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('music_genres', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 64);
			$table->unsignedInteger('review_id')->nullable();
			$table->unsignedInteger('user_tracking_session_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('music_genres');
	}

}
