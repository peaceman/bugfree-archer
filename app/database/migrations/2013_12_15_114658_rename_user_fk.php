<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RenameUserFk extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(
			'user_addresses',
			function (Blueprint $table) {
				$table->dropForeign('user_addresses_user_id_foreign');
				$table->foreign('user_id', 'ua_u_fk')
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
		Schema::table(
			'user_addresses',
			function (Blueprint $table) {
				$table->dropForeign('ua_u_fk');
				$table->foreign('user_id')
					->references('id')->on('users');
			}
		);
	}

}
