<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('productions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->double('amount');
			$table->integer('term_id')->unsigned();
			$table->enum('rate',[0,1,2,3,4,5,6,7,8,9,10])->default(5);
			$table->text('note')->nullable();
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
		Schema::drop('productions');
	}

}
