<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStateToUserEmailConfirmationsTable extends Migration
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
				$table->enum('state', ['unused', 'used', 'deactivated', 'expired'])
					->after('hash')->default('unused');
			}
		);

		DB::update("UPDATE `user_email_confirmations` SET `state` = 'used' WHERE `used` = 1");

		Schema::table(
			'user_email_confirmations',
			function (Blueprint $table) {
				$table->dropColumn('used');
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
				$table->boolean('used')->default(false);
			}
		);

		DB::update("UPDATE `user_email_confrmations` SET `used` = 1 WHERE `state` = 'used'");

		Schema::table(
			'user_email_confirmations',
			function (Blueprint $table) {
				$table->dropColumn('state');
			}
		);
	}

}
