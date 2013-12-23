<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSettingsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'user_settings',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('user_id');
				$table->string('name', 16);
				$table->string('value');
				$table->timestamps();

				$table->foreign('user_id', 'us_u_fk')
					->references('id')->on('users');
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
		Schema::drop('user_settings');
	}

}
