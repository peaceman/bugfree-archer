<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopItemRatingsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_item_ratings', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('shop_item_id');
			$table->unsignedInteger('user_tracking_session_id');
			$table->unsignedInteger('rating');
			$table->timestamps();

			$table->foreign('shop_item_id', 'sir_si_fk')
				->references('id')->on('shop_items')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('user_tracking_session_id', 'sir_uts_fk')
				->references('id')->on('user_tracking_sessions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_item_ratings');
	}

}
