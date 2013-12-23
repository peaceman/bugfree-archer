<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTrackingVisistedUrlsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'user_tracking_visited_urls',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('session_id');
				$table->string('url');
				$table->timestamps();

				$table->foreign('session_id', 'utvu_uts_fk')
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
		Schema::drop('user_tracking_visited_urls');
	}

}
