<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAddressesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'user_addresses',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('user_id');
				$table->unsignedInteger('country_id');
				$table->string('locality');
				$table->string('postcode');
				$table->mediumText('address_lines');

				$table->timestamps();

				$table->foreign('user_id')
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
		Schema::drop('user_addresses');
	}

}
