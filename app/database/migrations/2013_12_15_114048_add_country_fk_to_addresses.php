<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCountryFkToAddresses extends Migration
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
				$table->dropColumn('country_id');
			}
		);

		Schema::table(
			'user_addresses',
			function (Blueprint $table) {
				$table->integer('country_id');

				$table->foreign('country_id', 'ua_c_fk')
					->references('id')->on('countries')
					->onDelete('restrict');
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
				$table->dropForeign('ua_c_fk');
			}
		);

		Schema::table(
			'user_addresses',
			function (Blueprint $table) {
				$table->unsignedInteger('country_id');
			}
		);
	}

}
