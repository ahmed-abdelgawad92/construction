<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('def_num');
			$table->string('address')->nullable();
			$table->string('village')->nullable();
			$table->string('center')->nullable();
			$table->string('city')->nullable();
			$table->string('extra_data')->nullable();
			$table->string('model_used')->nullable();
			$table->string('implementing_period')->nullable();
			$table->integer('organization_id')->unsigned();
			$table->double('non_organization_payment')->nullable();
			$table->double('approximate_price');
			$table->timestamps('started_at');
			$table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
