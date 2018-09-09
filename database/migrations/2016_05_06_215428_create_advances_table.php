<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('advances', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->integer('employee_project_id')->unsigned();
			$table->double('advance');
			$table->enum('active',[0,1]);
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
		Schema::table('advances', function(Blueprint $table)
		{
			//
			Schema::drop('advances');
		});
	}

}
