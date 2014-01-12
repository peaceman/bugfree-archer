<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFileRevisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_file_revisions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('music_genre_id');
			$table->unsignedInteger('resource_file_id');
			$table->smallInteger('bpm', false, true);
			$table->text('description');
			$table->timestamps();

			$table->foreign('music_genre_id')
				->references('id')->on('music_genres')
				->onUpdate('cascade')->onDelete('restrict');

			$table->foreign('resource_file_id')
				->references('id')->on('resource_files')
				->onUpdate('cascade')->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('project_file_revisions');
	}

}
