<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCanContainItemsToShopCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shop_categories', function(Blueprint $table)
		{
			$table->boolean('can_contain_items')
                ->default(false)
                ->after('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shop_categories', function(Blueprint $table)
		{
			$table->dropColumn('can_contain_items');
		});
	}

}