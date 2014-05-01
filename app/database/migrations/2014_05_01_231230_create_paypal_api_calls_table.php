<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaypalApiCallsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paypal_api_calls', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('shop_order_id');
			$table->binary('request');
			$table->binary('response');
			$table->timestamps();

			$table->foreign('shop_order_id', 'pac_so_fk')
				->references('id')->on('shop_orders')
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
		Schema::drop('paypal_api_calls');
	}

}
