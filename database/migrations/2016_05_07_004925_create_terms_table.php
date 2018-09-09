<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->string('code');
			$table->string('statement');
			$table->string('unit');
			$table->double('amount');
			$table->double('value');
			$table->enum('done',[0,1])->default(0);
			$table->integer('project_id')->unsigned();
			$table->integer('contractor_id')->nullable()->unsigned();
			$table->double('contractor_unit_price')->nullable();
			$table->longText('contract_text')->nullable();
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
		Schema::drop('terms');
	}

}
