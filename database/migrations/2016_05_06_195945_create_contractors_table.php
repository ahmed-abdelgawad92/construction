<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('address')->nullable();
			$table->string('center',20)->nullable();
			$table->string('city',20)->nullable();
			$table->string('phone')->nullable();
			$table->string('type')->nullable();
			$table->integer('user_id')->nullable()->unsigned()->unique();
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
		Schema::drop('contractors');
	}

}
