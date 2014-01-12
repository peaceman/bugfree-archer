<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemRevisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_item_revisions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('shop_item_id');
			$table->unsignedInteger('shop_category_id');
			$table->unsignedInteger('product_revision_id');
			$table->string('product_revision_type', 32);
			$table->string('price', 16);
			$table->string('title', 128);
			$table->string('slug', 32);
			$table->timestamps();

			$table->foreign('shop_item_id')
				->references('id')->on('shop_items')
				->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('shop_category_id')
				->references('id')->on('shop_categories')
				->onUpdate('cascade')->onDelete('restrict');

			$table->index('product_revision_type');
			$table->unique('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_item_revisions');
	}

}
