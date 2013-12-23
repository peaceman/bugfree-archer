<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FixUserFk extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(
			'user_email_confirmations',
			function (Blueprint $table) {
				$table->dropForeign('uec_u_fk');
				$table->foreign('user_id', 'uec_u_fk')
					->references('id')->on('users')
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
		Schema::table(
			'user_email_confirmations',
			function (Blueprint $table) {
				$table->dropForeign('uec_u_fk');
				$table->foreign('user_id', 'uec_u_fk')
					->references('id')->on('users');
			}
		);
	}

}
