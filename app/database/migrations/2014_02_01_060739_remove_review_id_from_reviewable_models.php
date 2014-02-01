<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveReviewIdFromReviewableModels extends Migration {

	static $tables = ['music_genres', 'music_programs', 'music_plugins', 'music_banks'];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		foreach (static::$tables as $table) {
			Schema::table($table, function(Blueprint $table)
			{
				$table->dropColumn('review_id');
			});			
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		foreach (static::$tables as $table) {
			Schema::table('music_genres', function(Blueprint $table)
			{
				$table->unsignedInteger('review_id')
					->nullable()
					->after('id');
			});	
		}
	}

}