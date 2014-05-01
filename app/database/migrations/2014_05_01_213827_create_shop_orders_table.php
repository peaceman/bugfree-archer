<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_tracking_session_id');
			$table->unsignedInteger('shop_item_revision_id');
			$table->string('payment_state', 16);
			$table->timestamps();

			$table->foreign('user_tracking_session_id', 'so_uts_fk')
				->references('id')->on('user_tracking_sessions');

			$table->foreign('shop_item_revision_id', 'so_sir_fk')
				->references('id')->on('shop_item_revisions')
				->onUpdate('cascade');

			$table->index('payment_state');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_orders');
	}

}
