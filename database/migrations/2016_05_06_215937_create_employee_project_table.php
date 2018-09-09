<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeProjectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_project', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->integer('employee_id')->unsigned();
			$table->double('salary');
			$table->enum('done',[0,1]);
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
		Schema::table('employee_project', function(Blueprint $table)
		{
			//
			Schema::drop('employee_project');
		});
	}

}
