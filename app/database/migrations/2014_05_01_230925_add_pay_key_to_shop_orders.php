<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPayKeyToShopOrders extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shop_orders', function (Blueprint $table) {
			$table->string('paypal_pay_key', 32)
				->nullable()
				->after('payment_state');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shop_orders', function (Blueprint $table) {
			$table->dropColumn('paypal_pay_key');
		});
	}

}
