<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopItemRevisionFilesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_item_revision_files', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('shop_item_revision_id');
			$table->unsignedInteger('resource_file_id');
			$table->string('file_type');
			$table->timestamps();

			$table->foreign('shop_item_revision_id')
				->references('id')->on('shop_item_revisions')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('resource_file_id')
				->references('id')->on('resource_files')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->index('file_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_item_revision_files');
	}

}
