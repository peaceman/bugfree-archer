<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('owner_id');
			$table->string('state', 32);
			$table->timestamps();

			$table->foreign('owner_id', 'si_u_fk')
				->references('id')->on('users')
				->onUpdate('cascade')->onDelete('restrict');

			$table->index('state');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_items');
	}

}
