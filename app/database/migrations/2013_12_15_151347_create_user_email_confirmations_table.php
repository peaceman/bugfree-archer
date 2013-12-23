<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserEmailConfirmationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'user_email_confirmations',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('user_id');
				$table->string('hash');
				$table->boolean('used')->default(false);
				$table->timestamps();

				$table->foreign('user_id', 'uec_u_fk')
					->references('id')->on('users');

				$table->index('hash');
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
		Schema::drop('user_email_confirmations');
	}

}
