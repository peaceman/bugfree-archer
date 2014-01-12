<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reviews', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('reviewer_id')->default(0);
			$table->string('state', 16);
			$table->string('reviewee_type', 32);
			$table->boolean('result')->nullable();
			$table->text('result_reasoning')->nullable();
			$table->timestamps();

			$table->index('state');
			$table->index('reviewee_type');

			$table->foreign('reviewer_id', 'r_u_fk')
				->references('id')->on('users')
				->onUpdate('cascade')
				->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reviews');
	}

}
