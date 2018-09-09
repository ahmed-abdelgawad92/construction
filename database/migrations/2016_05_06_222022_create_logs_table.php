<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->integer('user_id');
			$table->string('action',20);
			$table->string('table',20);
			$table->integer('record_id');
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
		Schema::table('logs', function(Blueprint $table)
		{
			//
			Schema::drop('logs');
		});
	}

}
