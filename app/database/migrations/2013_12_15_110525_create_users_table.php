<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'users',
			function (Blueprint $table) {
				$table->increments('id');
				$table->string('email')->unique();
				$table->string('username')->unique();
				$table->string('password');
				$table->string('real_name');
				$table->enum('state', ['unconfirmed_email', 'active', 'inactive', 'tmp_ban', 'perma_ban']);
				$table->timestamps();
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
		Schema::drop('users');
	}

}
