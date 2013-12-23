<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTrackingSessionsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'user_tracking_sessions',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('user_id');
				$table->string('ip_address', 39);
				$table->unsignedInteger('useragent_id');
				$table->timestamps();

				$table->foreign('user_id', 'uts_u_fk')
					->references('id')->on('users')
					->onDelete('cascade');

				$table->foreign('useragent_id', 'uts_ua_fk')
					->references('id')->on('useragents')
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
		Schema::drop('user_tracking_sessions');
	}

}
