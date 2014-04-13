<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUserTrackingSessionIdToShopItemRevisions extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shop_item_revisions', function (Blueprint $table) {
			$table->unsignedInteger('user_tracking_session_id')
				->nullable()
				->default(null)
				->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shop_item_revisions', function (Blueprint $table) {
			$table->dropColumn('user_tracking_session_id');
		});
	}

}
