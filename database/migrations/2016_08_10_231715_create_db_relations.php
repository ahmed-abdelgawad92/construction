<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDbRelations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		//Create 1 to m relation between Project and Organizations
		Schema::table('projects',function(Blueprint $table){
			$table->foreign('organization_id')->references('id')->on('organizations');
		});
		
		//Create 1 to m relation between Raw Supplier and Stores
		//Create 1 to m relation between Projects and Stores
		//Create m to m relation between Projects and Raw Suppliers
		//The Stores table in fact is a bridge table between Raw Suppliers and Projects
		Schema::table('stores',function(Blueprint $table){
			$table->foreign('supplier_id')->references('supplier_id')->on('raw_suppliers');
			$table->foreign('project_id')->references('id')->on('projects');			
		});

		//Create 1 to m relation between Terms and Production
		Schema::table('productions',function(Blueprint $table){
			$table->foreign('term_id')->references('id')->on('terms');
		});

		//Create 1 to m relation between Terms and Consumption
		Schema::table('consumptions',function(Blueprint $table){
			$table->foreign('term_id')->references('id')->on('terms');
		});

		//Create 1 to m relation between Project and Taxes
		Schema::table('taxes',function(Blueprint $table){
			$table->foreign('project_id')->references('id')->on('projects');
		});

		//Create 1 or 0 to 1 relation between Users and Contractors
		Schema::table('contractors',function(Blueprint $table){
			$table->foreign('user_id')->references('id')->on('users');
		});

		//Create 1 to 0 or 1 relation between Contractors and Labor Supplier
		Schema::table('labor_suppliers',function(Blueprint $table){
			$table->foreign('contractor_id')->references('id')->on('contractors');
		});

		//Create 1 to 0 or 1 relation between Contractors and Raw Supplier
		Schema::table('raw_suppliers',function(Blueprint $table){
			$table->foreign('contractor_id')->references('id')->on('contractors');
		});

		//Create 1 to m relation between Employee_Project and Advances
		Schema::table('advances',function(Blueprint $table){
			$table->foreign('employee_project_id')->references('id')->on('employee_project');
		});

		//Create m to m relation between Projects and Employees
		//The employee_project table in fact is a bridge table between Employees and Projects
		Schema::table('employee_project',function(Blueprint $table){
			$table->foreign('project_id')->references('id')->on('projects');
			$table->foreign('employee_id')->references('id')->on('employees');
		});

		//Create 1 to m relation between Projects and Terms
		//Create 1 to m relation between Contractors and Terms
		Schema::table('terms',function(Blueprint $table){
			$table->foreign('project_id')->references('id')->on('projects');
			$table->foreign('contractor_id')->references('contractor_id')->on('labor_suppliers');
		});

		//Create 1 to m relation between Projects and Graphs
		Schema::table('graphs',function(Blueprint $table){
			$table->foreign('project_id')->references('id')->on('projects');
		});

		//Create 1 to m relation between Projects and Expenses
		Schema::table('expenses',function(Blueprint $table){
			$table->foreign('project_id')->references('id')->on('projects');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
