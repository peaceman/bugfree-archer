<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPayoutDetailsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_payout_details', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('user_tracking_session_id');
			$table->string('paypal_email');
			$table->timestamps();

			$table->foreign('user_id', 'upd_u_fk')
				->references('id')->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('user_tracking_session_id', 'upd_uts_fk')
				->references('id')->on('user_tracking_sessions')
				->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_payout_details');
	}

}
