<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopItemCommentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_item_comments', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('shop_item_id');
			$table->unsignedInteger('user_tracking_session_id');
			$table->text('content');
			$table->timestamps();

			$table->foreign('shop_item_id', 'sic_si_fk')
				->references('id')->on('shop_items')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('user_tracking_session_id', 'sic_uts_fk')
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
		Schema::drop('shop_item_comments');
	}

}
