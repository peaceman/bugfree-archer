<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemRevisionImagesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_item_revision_images', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('shop_item_revision_id');
			$table->unsignedInteger('resource_image_id');
			$table->string('image_type');
			$table->timestamps();

			$table->foreign('shop_item_revision_id', 'siri_sir_fk')
				->references('id')->on('shop_item_revisions');

			$table->foreign('resource_image_id', 'siri_ri_fk')
				->references('id')->on('resource_images');

			$table->index('image_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_item_revision_images');
	}

}
