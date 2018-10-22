<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);


//LOGIN Routes
Route::get('/', [
  'uses'=>'Auth\LoginController@getLogin',
  'as'=>'login'
]);
Route::post('/',[
  'uses'=>'Auth\LoginController@authenticate',
  'as'=>'postLogin'
]);


//Authenticated Routes
Route::group(['middleware' => 'auth'], function() {
    //
    Route::get('/home',function(){
			return view('home',['active'=>'home']);
		}
	 );
    Route::get('dashboard',[
		'as'=>'dashboard',
		function(){
			return view('home',['active'=>'home']);
		}
	]);
  Route::get("/logout",[
    'uses'=>'Auth\LoginController@logout',
    'as'=>'logout'
  ]);

	//organization manipulations
	Route::group(['prefix' => 'organization'], function() {

	    //Add Organization to the db
	    Route::get('add',[
	    	'uses'=>'OrganizationController@create',
	    	'as'=>'addorganization'
	    ]);
	    Route::post('add',[
	    	'uses'=>'OrganizationController@store',
	    	'as'=>'addorganization'
	    ]);

	    //Update Organization in the db
	    Route::put('update/{id}',[
	    	'uses'=>'OrganizationController@update',
	    	'as'=>'updateorganization'
	    ])->where('id', '[0-9]+');
	    Route::get('update/{id}',[
	    	'uses'=>'OrganizationController@edit',
	    	'as'=>'updateorganization'
	    ])->where('id', '[0-9]+');

	    //Delete Organization from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'OrganizationController@destroy',
	    	'as'=>'deleteorganization'
	    ])->where('id', '[0-9]+');

	    //Show All Organizations
	    Route::get('all',[
	    	'uses'=>'OrganizationController@index',
	    	'as'=>'allorganization'
	    ]);

	    //Show All clients
	    Route::get('all/clients',[
	    	'uses'=>'OrganizationController@getClients',
	    	'as'=>'allclients'
	    ]);

	    //Show All contract clients
	    Route::get('all/contractor',[
	    	'uses'=>'OrganizationController@getContractClients',
	    	'as'=>'allcontractclients'
	    ]);


	    //find by id
	    Route::get('/{id}',[
	    	'uses'=>'OrganizationController@show',
	    	'as'=>'showorganization'
	    ])->where('id', '[0-9]+');
	});

	//Project Manipulations
	Route::group(['prefix' => 'project'], function() {

	    //Add Project to the db
	    Route::get('add/{id?}',[
	    	'uses'=>'ProjectController@create',
	    	'as'=>'addproject'
	    ]);
	    Route::post('add',[
	    	'uses'=>'ProjectController@store',
	    	'as'=>'addproject'
	    ]);

	    //Update Project in the db
	    Route::put('update/{id}',[
	    	'uses'=>'ProjectController@update',
	    	'as'=>'updateproject'
	    ])->where('id', '[0-9]+');
	    Route::get('update/{id}',[
	    	'uses'=>'ProjectController@edit',
	    	'as'=>'updateproject'
	    ])->where('id', '[0-9]+');

	    //Delete Project from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'ProjectController@destroy',
	    	'as'=>'deleteproject'
	    ])->where('id', '[0-9]+');

	    //Show All Projects
	    Route::get('all',[
	    	'uses'=>'ProjectController@index',
	    	'as'=>'allproject'
	    ]);
	    //Show allstartedproject
	    Route::get('current',[
	    	'uses'=>'ProjectController@allStarted',
	    	'as'=>'allstartedproject'
	    ]);

	    //Show allstartedproject
	    Route::get('done',[
	    	'uses'=>'ProjectController@allDone',
	    	'as'=>'alldoneproject'
	   	]);

	    //Show allstartedproject
	    Route::get('notstarted',[
	    	'uses'=>'ProjectController@allNotStarted',
	    	'as'=>'allnotstartedproject'
	   	]);

      //Add Cash box
      Route::put('{id}/add/cash_box',[
        'uses'=>'ProjectController@addCashBox',
        'as'=>'add_cash_box'
      ])->where('id','[0-9]+');
      //Add Loan
      Route::put('{id}/add/loan',[
        'uses'=>'ProjectController@addLoan',
        'as'=>'add_loan'
      ])->where('id','[0-9]+');

	   	//start project
	    Route::put('start/{id}',[
	    	'uses'=>'ProjectController@startProject',
	    	'as'=>'startproject'
	   	])->where('id','[0-9]+');

	   	//start project
	    Route::put('end/{id}',[
	    	'uses'=>'ProjectController@endProject',
	    	'as'=>'endproject'
	   	])->where('id','[0-9]+');

	    //find by id
	    Route::get('/{id}',[
	    	'uses'=>'ProjectController@show',
	    	'as'=>'showproject'
	    ])->where('id', '[0-9]+');
	    //Non-Organization Payment
	    Route::get('addNonOrg',[
	    	'uses'=>'ProjectController@checkNonOrgCost',
	    	'as'=>'nonorg'
    	]);
    	Route::put('add/non_org_cost/{id}',[
	    	'uses'=>'ProjectController@addNonOrgCost',
	    	'as'=>'addnonorg'
    	])->where('id','[0-9]+');

	});

	//User Manipulations
	Route::group(['prefix' => 'user'], function() {

	    //Add User to the db
	    Route::get('add/{id?}',[
	    	'uses'=>'UserController@create',
	    	'as'=>'adduser'
	    ])->where('id','[0-9]+');

	    Route::post('add',[
	    	'uses'=>'UserController@store',
	    	'as'=>'adduser'
	    ]);

	    //Update User in the db
	    Route::put('update/{id}',[
	    	'uses'=>'UserController@update',
	    	'as'=>'updateuser'
	    ])->where('id', '[0-9]+');

	    Route::get('update/{id}',[
	    	'uses'=>'UserController@edit',
	    	'as'=>'updateuser'
	    ])->where('id', '[0-9]+');

	    //Delete User from the db
	    Route::delete('delete/{id}',[
	    	'uses'=>'UserController@destroy',
	    	'as'=>'deleteuser'
	    ])->where('id', '[0-9]+');

	    //Show All Users
	    Route::get('all',[
	    	'uses'=>'UserController@index',
	    	'as'=>'alluser'
	    ]);

	    //Show All Users
	    Route::get('all/contractors',[
	    	'uses'=>'UserController@contractor',
	    	'as'=>'allcontractor'
	    ]);

	    //Show All Admin Users
	    Route::get('all/admins',[
	    	'uses'=>'UserController@admin',
	    	'as'=>'alladmin'
	    ]);

	    //find User by id
	    Route::get('/{id}',[
	    	'uses'=>'UserController@show',
	    	'as'=>'showuser'
	    ])->where('id', '[0-9]+');
	});

	//Term Manipulations
	Route::group(['prefix' => 'term'], function() {

	    //Add Term to the db
	    Route::get('add/{id?}',[
	    	'uses'=>'TermController@create',
	    	'as'=>'addterm'
	    ])->where('id','[0-9]+');
	    Route::post('add',[
	    	'uses'=>'TermController@store',
	    	'as'=>'addterm'
	    ]);

      //Get Statement
      Route::get('get_statement/{code}',[
        'uses'=>'TermController@getStatement',
        'as'=>'get_statement'
        ])->where('code','[0-9\/\s]+');

	    //Update Term in the db
	    Route::put('update/{id}',[
	    	'uses'=>'TermController@update',
	    	'as'=>'updateterm'
	    ])->where('id', '[0-9]+');

	    Route::get('update/{id}',[
	    	'uses'=>'TermController@edit',
	    	'as'=>'updateterm'
	    ])->where('id', '[0-9]+');

	    //Show All Terms
	    Route::get('all/{id?}',[
	    	'uses'=>'TermController@index',
	    	'as'=>'allterm'
	    ])->where('id','[0-9]+');

      //show all not started terms
      Route::get("all/not_started/{id}",[
          'uses'=>'TermController@getNotstartedTerms',
          'as'=>'notstartedterms'
      ])->where('id','[0-9]+');
      //show all started terms
      Route::get("all/started/{id}",[
        'uses'=>'TermController@getStartedTerms',
        'as'=>'startedterms'
        ])->where('id','[0-9]+');
      //show all disabled terms
      Route::get("all/disabled/{id}",[
          'uses'=>'TermController@getDisabledTerms',
          'as'=>'disabledterms'
      ])->where('id','[0-9]+');
      //show all done terms
      Route::get("all/done/{id}",[
          'uses'=>'TermController@getDoneTerms',
          'as'=>'doneterms'
      ])->where('id','[0-9]+');
      //show all deleted terms
      Route::get("all/deleted/{id}",[
          'uses'=>'TermController@getDeletedTerms',
          'as'=>'deletedterms'
      ])->where('id','[0-9]+');

	    //find Term by id
	    Route::get('/{id}',[
	    	'uses'=>'TermController@show',
	    	'as'=>'showterm'
	    ])->where('id', '[0-9]+');

      //Delete Term from the db
	    Route::get('delete/{id}',[
	    	'uses'=>'TermController@destroy',
	    	'as'=>'deleteterm'
	    ])->where('id', '[0-9]+');
	   	//Start Term by id
	    Route::get('start/{id}',[
	    	'uses'=>'TermController@startTerm',
	    	'as'=>'startterm'
	    ])->where('id', '[0-9]+');
	    //stop term by id
	    Route::get('end/{id}',[
	    	'uses'=>'TermController@endTerm',
	    	'as'=>'endterm'
	    ])->where('id','[0-9]+');
	    //enable term by id
	    Route::get('enable/{id}',[
	    	'uses'=>'TermController@enableTerm',
	    	'as'=>'enableterm'
	    ])->where('id','[0-9]+');
	    //disable term by id
	    Route::get('disable/{id}',[
	    	'uses'=>'TermController@disableTerm',
	    	'as'=>'disableterm'
	    ])->where('id','[0-9]+');
	    //restore term by id
	    Route::get('restore/{id}',[
	    	'uses'=>'TermController@restoreTerm',
	    	'as'=>'restoreterm'
	    ])->where('id','[0-9]+');


	    //contract term
	    Route::get('contract/{id}',[
	    	'uses'=>'TermController@getTermContract',
	    	'as'=>'termcontract'
	    ])->where('id','[0-9]+');

	    Route::put('contract/{id}',[
	    	'uses'=>'TermController@postTermContract',
	    	'as'=>'termcontract'
	    ])->where('id','[0-9]+');

	    //Add Term Type
	    Route::get('type',[
	    	'uses'=>'TermTypeController@create',
	    	'as'=>'addtermtype'
	    ]);
	    Route::post('type',[
	    	'uses'=>'TermTypeController@store',
	    	'as'=>'addtermtype'
	    ]);
	    //Edit Term Type
	    Route::get('type/update/{id}',[
	    	'uses'=>'TermTypeController@edit',
	    	'as'=>'updatetermtype'
	    ])->where('id','[0-9]+');
	    Route::put('type/update/{id}',[
	    	'uses'=>'TermTypeController@update',
	    	'as'=>'updatetermtype'
	    ])->where('id','[0-9]+');
	    //Delete Term Type
	    Route::delete('type/delete/{id}',[
	    	'uses'=>'TermTypeController@destroy',
	    	'as'=>'deletetermtype'
	    ])->where('id','[0-9]+');
	    //show all Term Types
	    Route::get('type/all',[
	    	'uses'=>'TermTypeController@index',
	    	'as'=>'alltermtype'
	    ]);
	});

	//Contracts Manipulations
	Route::group(['prefix' => 'contract'], function() {
    //Create Contract
    Route::get('create/{id}',[
      'uses'=>"ContractController@createFromTerm",
      'as'=>'addcontract'
    ])->where('id','[0-9]+');
    Route::post('create/{id}',[
      'uses'=>"ContractController@store",
      'as'=>'addcontract'
    ])->where('id','[0-9]+');

    //Update Contract
    Route::get('update/{id}',[
      'uses'=>"ContractController@edit",
      'as'=>'updatecontract'
    ])->where('id','[0-9]+');
    Route::put('update/{id}',[
      'uses'=>"ContractController@update",
      'as'=>'updatecontract'
    ])->where('id','[0-9]+');

    //End Contract
    Route::get('endcontract/{id}',[
      'uses'=>"ContractController@endContract",
      'as'=>'endcontract'
    ])->where('id','[0-9]+');
  });
	//Contractors Manipulations
	Route::group(['prefix' => 'contractor'], function() {
	    //Add Contractor
	    Route::get('add',[
	    	'uses'=>'ContractorController@create',
	    	'as'=>'addcontractor'
	    ]);
	    Route::post('add',[
	    	'uses'=>'ContractorController@store',
	    	'as'=>'addcontractor'
	    ]);
	    //update Contractor
	    Route::get('update/{id}',[
	    	'uses'=>'ContractorController@edit',
	    	'as'=>'updatecontractor'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'ContractorController@update',
	    	'as'=>'updatecontractor'
	    ])->where('id','[0-9]+');

	    //Delete Contractor
	    Route::delete('delete/{id}',[
	    	'uses'=>'ContractorController@destroy',
	    	'as'=>'deletecontractor'
	    ])->where('id','[0-9]+');

	    //find By ID
	    Route::get('show/{id}',[
	    	'uses'=>'ContractorController@show',
	    	'as'=>'showcontractor'
	    ])->where('id','[0-9]+');

	    //ALL CONTRACTORS
	    Route::get('all',[
	    	'uses'=>'ContractorController@index',
	    	'as'=>'allcontractor'
	    ]);

	    //ALL Labor Suppliers
	    Route::get('all/LaborSuppliers',[
	    	'uses'=>'ContractorController@getLabor',
	    	'as'=>'alllabor'
	    ]);

	    //ALL CONTRACTED TERMS
	    Route::get('{id}/all/terms',[
	    	'uses'=>'ContractorController@getAllTerms',
	    	'as'=>'ContractedTerms'
	    ])->where('id','[0-9]+');
	    //ALL Productions
	    Route::get('{id}/all/productions',[
	    	'uses'=>'ContractorController@getAllProductions',
	    	'as'=>'contractorproductions'
	    ])->where('id','[0-9]+');
	});


	//Suppliers Manipulations
	Route::group(['prefix' => 'supplier'], function() {
	    //Add Supplier
	    Route::get('add',[
	    	'uses'=>'SupplierController@create',
	    	'as'=>'addsupplier'
	    ]);
	    Route::post('add',[
	    	'uses'=>'SupplierController@store',
	    	'as'=>'addsupplier'
	    ]);
	    //update Supplier
	    Route::get('update/{id}',[
	    	'uses'=>'SupplierController@edit',
	    	'as'=>'updatesupplier'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'SupplierController@update',
	    	'as'=>'updatesupplier'
	    ])->where('id','[0-9]+');

	    //Delete Supplier
	    Route::delete('delete/{id}',[
	    	'uses'=>'SupplierController@destroy',
	    	'as'=>'deletesupplier'
	    ])->where('id','[0-9]+');

	    //find By ID
	    Route::get('show/{id}',[
	    	'uses'=>'SupplierController@show',
	    	'as'=>'showsupplier'
	    ])->where('id','[0-9]+');

	    //ALL Supplier
	    Route::get('all',[
	    	'uses'=>'SupplierController@index',
	    	'as'=>'allsupplier'
	    ]);

	    //ALL Supplier Stores
	    Route::get('{id}/all/stores',[
	    	'uses'=>'SupplierController@getAllStores',
	    	'as'=>'SuppliedStores'
	    ])->where('id','[0-9]+');
	    //ALL Paymets to a Supplier
	    Route::get('{id}/all/payments',[
	    	'uses'=>'SupplierController@getAllPayment',
	    	'as'=>'allsupplierPayments'
	    ])->where('id','[0-9]+');
	});


	//Store Manipulations
	Route::group(['prefix' => 'store'], function() {
	    //add store
		Route::get('add/{cid?}/{pid?}/{tid?}',[
			'uses'=>'StoreController@create',
			'as'=>'addstores'
		])->where(['cid'=>'[0-9]+','pid'=>'[0-9]+','tid'=>'[0-9]+']);

		Route::post('add',[
			'uses'=>'StoreController@store',
			'as'=>'addstore'
		]);
    //update store
		Route::get('edit/{id}',[
			'uses'=>'StoreController@edit',
			'as'=>'updatestore'
		]);
		Route::put('edit/{id}',[
			'uses'=>'StoreController@update',
			'as'=>'updatestore'
		]);
    //delete store
		Route::get('delete/{id}',[
			'uses'=>'StoreController@destroy',
			'as'=>'deletestore'
		]);

		//all stores for a project
		Route::get('all/project/{id}',[
			'uses'=>'StoreController@index',
			'as'=>'allstores'
		])->where('id','[0-9]+');

		Route::get('all/projects',[
			'uses'=>'StoreController@findStore',
			'as'=>'findstores'
		]);

		Route::post('all/',[
			'uses'=>'StoreController@getAll',
			'as'=>'findallstores'
		]);

		//show store
		Route::get('{id}/show/{type}',[
			'uses'=>'StoreController@show',
			'as'=>'showstore'
		]);
    //get all supplier of specific type
    Route::get("get_suppliers/{type?}",[
      'uses'=>'StoreController@getSuppliers',
      'as'=>'get_suppliers_ajax'
    ]);
    //Add Payment To a Store
    Route::put('add/payment/{id}',[
      'uses'=>'StoreController@addPayment',
      'as'=>'addPaymentToStore'
    ]);
    //ALL Paymets of a store
    Route::get('{id}/all/payments',[
      'uses'=>'StoreController@getAllPayment',
      'as'=>'allstorePayments'
    ])->where('id','[0-9]+');
		//Add Store Type
	    Route::get('type',[
	    	'uses'=>'StoreTypeController@create',
	    	'as'=>'addstoretype'
	    ]);
	    Route::post('type',[
	    	'uses'=>'StoreTypeController@store',
	    	'as'=>'addstoretype'
	    ]);
	    //Edit Store Type
	    Route::get('type/update/{id}',[
	    	'uses'=>'StoreTypeController@edit',
	    	'as'=>'updatestoretype'
	    ])->where('id','[0-9]+');
	    Route::put('type/update/{id}',[
	    	'uses'=>'StoreTypeController@update',
	    	'as'=>'updatestoretype'
	    ])->where('id','[0-9]+');
	    //Delete Store Type
	    Route::delete('type/delete/{id}',[
	    	'uses'=>'StoreTypeController@destroy',
	    	'as'=>'deletestoretype'
	    ])->where('id','[0-9]+');
	    //show all Store Types
	    Route::get('type/all',[
	    	'uses'=>'StoreTypeController@index',
	    	'as'=>'allstoretype'
	    ]);
	});

	//Production Manipulation
	Route::group(['prefix' => 'production'], function() {
	    //add new production
	    Route::get('add/{id}',[
	    	'uses'=>'ProductionController@create',
	    	'as'=>'addproduction'
	    ])->where('id','[0-9]+');
	    Route::post('add/{id}',[
	    	'uses'=>'ProductionController@store',
	    	'as'=>'addproduction'
	    ])->where('id','[0-9]+');

	    //update production
	    Route::get('update/{id}',[
	    	'uses'=>'ProductionController@edit',
	    	'as'=>'updateproduction'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'ProductionController@update',
	    	'as'=>'updateproduction'
	    ])->where('id','[0-9]+');

	    //delete production
	    Route::delete('delete/{id}',[
	    	'uses'=>'ProductionController@destroy',
	    	'as'=>'deleteproduction'
	    ])->where('id','[0-9]+');

	    /*
		 *show all production records of a term
		 *and the total production of this term
	    */
	    Route::get('term/all/{id}',[
	    	'uses'=>'ProductionController@show',
	    	'as'=>'showtermproduction'
	    ])->where('id','[0-9]+');

	    /*
		 *show all production records of a project
		 *and the total production of this project
	    */
	    Route::get('project/all/{id}',[
	    	'uses'=>'ProductionController@index',
	    	'as'=>'showprojectproduction'
	    ])->where('id','[0-9]+');

	    //show all projects to find production
	    Route::get('choose/project',[
	    	'uses'=>'ProductionController@chooseProject',
	    	'as'=>'findproduction'
	    ]);

	    //show all projects to find production
	    Route::get('find/project',[
	    	'uses'=>'ProductionController@chooseProject',
	    	'as'=>'findtermstoaddproduction'
	    ]);

	    //findproductionforterm
	    Route::get('chooseproject',[
	    	'uses'=>'ProductionController@chooseProject',
	    	'as'=>'findproductionforterm'
	    ]);

	    //Show All Terms To Add Production
	    Route::get('all/add/{id}',[
	    	'uses'=>'TermController@index',
	    	'as'=>'alltermstoaddproduction'
	    ])->where('id','[0-9]+');
	    //Show All Terms To show production
	    Route::get('all/show/{id}',[
	    	'uses'=>'TermController@index',
	    	'as'=>'alltermstoshowproduction'
	    ])->where('id','[0-9]+');

	});

	//Consumption Manipulation
	Route::group(['prefix' => 'consumption'], function() {
	    //add new consumption
	    Route::get('add/{id}',[
	    	'uses'=>'ConsumptionController@create',
	    	'as'=>'addconsumption'
	    ])->where('id','[0-9]+');
	    Route::post('add/{id}',[
	    	'uses'=>'ConsumptionController@store',
	    	'as'=>'addconsumption'
	    ])->where('id','[0-9]+');

	    //update consumption
	    Route::get('update/{id}',[
	    	'uses'=>'ConsumptionController@edit',
	    	'as'=>'updateconsumption'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'ConsumptionController@store',
	    	'as'=>'updateconsumption'
	    ])->where('id','[0-9]+');

	    //delete consumption
	    Route::delete('delete/{id}',[
	    	'uses'=>'ConsumptionController@destroy',
	    	'as'=>'deleteconsumption'
	    ])->where('id','[0-9]+');

	    /*
		 *show all consumption records of a term
		 *and the total consumption of this term
	    */
	    Route::get('term/all/{id}',[
	    	'uses'=>'ConsumptionController@index',
	    	'as'=>'showtermconsumption'
	    ])->where('id','[0-9]+');

	    /*
		 *show all consumption records of a project
		 *and the total consumption of this project
	    */
	    Route::get('project/all/{id}',[
	    	'uses'=>'ConsumptionController@indexProject',
	    	'as'=>'showprojectconsumption'
	    ])->where('id','[0-9]+');

	    //show term consumption of exact raw
	    Route::get('term/show/{id}/{type}',[
	    	'uses'=>'ConsumptionController@showTermConsumedRaw',
	    	'as'=>'showtermconsumedraw'
	    ])->where('id','[0-9]+');
	    //show project consumption of exact raw
	    Route::get('project/show/{id}/{type}',[
	    	'uses'=>'ConsumptionController@showProjectConsumedRaw',
	    	'as'=>'showprojectconsumedraw'
	    ])->where('id','[0-9]+');

	    //select project to show all consumption of a term or a project
	    //or total consumption of raw type for a project or term
	    Route::get('project/select/',[
	    	'uses'=>'ConsumptionController@selectProjectConsumption',
	    	'as'=>'selectprojectconsumption'
	    ]);
	    Route::get('project/term/select/',[
	    	'uses'=>'ConsumptionController@selectTermConsumption',
	    	'as'=>'selecttermconsumption'
	    ]);
	    Route::get('project/term/type/select/',[
	    	'uses'=>'ConsumptionController@selectTermConsumedRaw',
	    	'as'=>'selecttermconsumedraw'
	    ]);
	    Route::get('project/type/select/',[
	    	'uses'=>'ConsumptionController@selectProjectConsumedRaw',
	    	'as'=>'selectprojectconsumedraw'
	    ]);
	    //show all terms to show all consumption
	    Route::get('project/{id}/term/select',[
	    	'uses'=>'TermController@index',
	    	'as'=>'termconsumption'
	    ])->where('id','[0-9]+');

	    //select project and term to add consumption
	    Route::get('selectproject/add',[
	    	'uses'=>'ConsumptionController@selectProjectConsumedRaw',
	    	'as'=>'showprojecttoaddconsumption'
	    ]);
	    Route::get('project/{id}/term/add',[
	    	'uses'=>'TermController@index',
	    	'as'=>'showtermtoaddconsumption'
	    ])->where('id','[0-9]+');
	});

	//Expense Manipulations
	Route::group(['prefix' => 'expense'], function() {
	    //add expense
	    Route::get('add/{id?}',[
	    	'uses'=>'ExpenseController@create',
	    	'as'=>'addexpenses'
	    ])->where('id','[0-9]+');
	    Route::post('add',[
	    	'uses'=>'ExpenseController@store',
	    	'as'=>'addexpense'
	    ]);
	    //update expense
	    Route::get('update/{id}',[
	    	'uses'=>'ExpenseController@edit',
	    	'as'=>'updateexpense'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'ExpenseController@update',
	    	'as'=>'updateexpense'
	    ])->where('id','[0-9]+');
	    //show all expenses within a project
	    Route::get('show/{id}',[
	    	'uses'=>'ExpenseController@show',
	    	'as'=>'showexpense'
	    ])->where('id','[0-9]+');
	    //delete Expense
	    Route::delete('delete/{id}',[
	    	'uses'=>'ExpenseController@destroy',
	    	'as'=>'deleteexpense'
	    ])->where('id','[0-9]+');

	    //choose project to show expenses
	    Route::get('choose/project',[
	    	'uses'=>'ExpenseController@chooseProject',
	    	'as'=>'chooseprojectexpense'
	    ]);
	});

	//Tax Manipulations
	Route::group(['prefix' => 'tax'], function() {
	    //add Tax
	    Route::get('add/{id?}',[
	    	'uses'=>'TaxController@create',
	    	'as'=>'addtaxes'
	    ])->where('id','[0-9]+');
	    Route::post('add',[
	    	'uses'=>'TaxController@store',
	    	'as'=>'addtax'
	    ]);
	    //update Tax
	    Route::get('update/{id}',[
	    	'uses'=>'TaxController@edit',
	    	'as'=>'updatetax'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'TaxController@update',
	    	'as'=>'updatetax'
	    ])->where('id','[0-9]+');
	    //show all Tax within a project
	    Route::get('show/{id}',[
	    	'uses'=>'TaxController@show',
	    	'as'=>'showtax'
	    ])->where('id','[0-9]+');
	    //delete Tax
	    Route::delete('delete/{id}',[
	    	'uses'=>'TaxController@destroy',
	    	'as'=>'deletetax'
	    ])->where('id','[0-9]+');

	    //choose project to show Tax
	    Route::get('choose/project',[
	    	'uses'=>'TaxController@chooseProject',
	    	'as'=>'chooseprojecttax'
	    ]);
	});

	//Graph Manipulations
	Route::group(['prefix' => 'graph'], function() {
	    //add graph
	    Route::get('add/{id?}',[
	    	'uses'=>'GraphController@create',
	    	'as'=>'addgraphs'
	    ])->where('id','[0-9]+');
	    Route::post('add',[
	    	'uses'=>'GraphController@store',
	    	'as'=>'addgraph'
	    ]);
	    //show graph
	    Route::get('show/{id}',[
	    	'uses'=>'GraphController@show',
	    	'as'=>'showgraph'
	    ])->where('id','[0-9]+');
	    //show PDF
	    Route::get('show/pdf/{fileName}',[
	    	'uses'=>'GraphController@showPdf',
	    	'as'=>'showPdf'
	    ]);

	    //update graph
	    Route::get('update/{id}',[
	    	'uses'=>'GraphController@edit',
	    	'as'=>'updategraph'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'GraphController@update',
	    	'as'=>'updategraph'
	    ])->where('id','[0-9]+');

	    //delete graph
	    Route::delete('delete/{id}',[
	    	'uses'=>'GraphController@destroy',
	    	'as'=>'deletegraph'
	    ])->where('id','[0-9]+');

	    //show projects to select one and show its graphs
	    Route::get('choose/project',[
	    	'uses'=>'GraphController@chooseProject',
	    	'as'=>'chooseprojectgraph'
	    ]);

	    //show all graphs of a project
	    Route::get('project/{id}/all',[
	    	'uses'=>'GraphController@index',
	    	'as'=>'allgraph'
	    ])->where('id','[0-9]+');
	});

	//Employee Manipulations
	Route::group(['prefix' => 'employee'], function() {
	    //add employee
	    Route::get('add',[
	    	'uses'=>'EmployeeController@create',
	    	'as'=>'addemployee'
	    ]);
	    Route::post('add',[
	    	'uses'=>'EmployeeController@store',
	    	'as'=>'addemployee'
	    ]);

	    //show employee
	    Route::get('show/{id}',[
	    	'uses'=>'EmployeeController@show',
	    	'as'=>'showemployee'
	    ])->where('id','[0-9]+');
	    //show company employee
	    Route::get('company/show/{id}',[
	    	'uses'=>'EmployeeController@showCompanyEmployee',
	    	'as'=>'showcompanyemployee'
	    ])->where('id','[0-9]+');

	    //update employee
	    Route::get('update/{id}',[
	    	'uses'=>'EmployeeController@edit',
	    	'as'=>'updateemployee'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'EmployeeController@update',
	    	'as'=>'updateemployee'
	    ])->where('id','[0-9]+');

	    //update employee
	    Route::get('company/update/{id}',[
	    	'uses'=>'EmployeeController@editCompany',
	    	'as'=>'updatecompanyemployee'
	    ])->where('id','[0-9]+');
	    Route::put('company/update/{id}',[
	    	'uses'=>'EmployeeController@updateCompany',
	    	'as'=>'updatecompanyemployee'
	    ])->where('id','[0-9]+');

	    //update salary
	    Route::put('update/salary/{eid}/{pid}',[
	    	'uses'=>'EmployeeController@updateSalary',
	    	'as'=>'updatesalary'
	    ])->where(['eid'=>'[0-9]+','pid'=>'[0-9]+']);

	    //delete employee
	    Route::delete('delete/{id}',[
	    	'uses'=>'EmployeeController@destroy',
	    	'as'=>'deleteemployee'
	    ])->where('id','[0-9]+');
	    //delete company employee
	    Route::delete('company/delete/{id}',[
	    	'uses'=>'EmployeeController@destroyCompany',
	    	'as'=>'deletecompanyemployee'
	    ])->where('id','[0-9]+');

	    //show projects to select one and show its graphs
	    Route::get('choose/project',[
	    	'uses'=>'EmployeeController@chooseProject',
	    	'as'=>'chooseprojectemployee'
	    ]);

	    //show all employees of a project
	    Route::get('project/all/{id?}',[
	    	'uses'=>'EmployeeController@index',
	    	'as'=>'allemployee'
	    ])->where('id','[0-9]+');

	    //show all employees of the company
	    Route::get('company',[
	    	'uses'=>'EmployeeController@company',
	    	'as'=>'allcompanyemployee'
	    ])->where('id','[0-9]+');

	    //show all projects of an employee
	    Route::get('assined/projects/{id}',[
	    	'uses'=>'EmployeeController@allProjects',
	    	'as'=>'employeeprojects'
	    ])->where('id','[0-9]+');
	    //assign a job to an employee
	    Route::get('assign/job/{id}',[
	    	'uses'=>'EmployeeController@getAssignJob',
	    	'as'=>'assignjob'
	    ]);
	    Route::post('assign/job/{id}',[
	    	'uses'=>'EmployeeController@assignJob',
	    	'as'=>'assignjob'
	    ]);

	    //end a job of an employee
	    Route::put('end/job/{eid}/{pid}',[
	    	'uses'=>'EmployeeController@endJob',
	    	'as'=>'endjob'
	    ])->where(['eid'=>'[0-9]+','pid'=>'[0-9]+']);
	});

	//Advance Manipulations
	Route::group(['prefix' => 'advance'], function() {
	    //add company advance
	    Route::get('add/company/{id?}',[
	    	'uses'=>'AdvanceController@createCompany',
	    	'as'=>'addcompanyadvances'
	    ])->where('id','[0-9]+');
	    Route::post('add/company',[
	    	'uses'=>'AdvanceController@storeCompany',
	    	'as'=>'addcompanyadvance'
	    ]);
	    //add advance
	    Route::get('add/{id?}',[
	    	'uses'=>'AdvanceController@create',
	    	'as'=>'addadvances'
	    ])->where('id','[0-9]+');
	    Route::post('add',[
	    	'uses'=>'AdvanceController@store',
	    	'as'=>'addadvance'
	    ]);
	    //show all advances of an employee
	    Route::get('show/{id}',[
	    	'uses'=>'AdvanceController@show',
	    	'as'=>'showadvance'
	    ])->where('id','[0-9]+');

	    Route::get('show/company/{id}',[
	    	'uses'=>'AdvanceController@showCompany',
	    	'as'=>'showcompanyadvance'
	    ])->where('id','[0-9]+');

	    //show all advances of the company
	    Route::get('all',[
	    	'uses'=>'AdvanceController@index',
	    	'as'=>'alladvance'
	    ])->where('id','[0-9]+');

	    //update advance
	    Route::get('update/{id}',[
	    	'uses'=>'AdvanceController@edit',
	    	'as'=>'updateadvance'
	    ])->where('id','[0-9]+');
	    Route::put('update/{id}',[
	    	'uses'=>'AdvanceController@update',
	    	'as'=>'updateadvance'
	    ])->where('id','[0-9]+');

	    //repay advance
	    Route::put('repay/{id}',[
	    	'uses'=>'AdvanceController@repay',
	    	'as'=>'repayadvance'
	    ])->where('id','[0-9]+');

	    //delete advance
	    Route::delete('delete/{id}',[
	    	'uses'=>'AdvanceController@destroy',
	    	'as'=>'deleteadvance'
	    ])->where('id','[0-9]+');
	});

	//Transactions Manipulations
	Route::group(['prefix' => 'transactions'], function() {
	    //Create Extract
		Route::get('create/extractor/{id}',[
			'uses'=>'TransactionController@create',
			'as'=>'createextractor'
		])->where('id','[0-9]+');
	    //Save Extract
		Route::post('save/extractor/{id}',[
			'uses'=>'TransactionController@store',
			'as'=>'saveextractor'
		]);
		//print extractor
		Route::get('print/{id}',[
			'uses'=>'TransactionController@printTable',
			'as'=>'printextractor'
		])->where('id','[0-9]+');
	    //Display All Projects to create extract or view total transactions
		Route::get('all/projects/show/extractor',[
			'uses'=>'TransactionController@chooseProject',
			'as'=>'chooseprojectextractor'
		]);
		//Display All Projects to create extract or view total transactions
		Route::get('all/projects/show/total_transaction',[
			'uses'=>'TransactionController@chooseProject',
			'as'=>'chooseprojecttransaction'
		]);
	    //Total Transactions of a project
		Route::get('total/project/{id}',[
			'uses'=>'TransactionController@index',
			'as'=>'alltransaction'
		])->where('id','[0-9]+');
		//Total Transactions of a term
		Route::get('total/term/{id}',[
			'uses'=>'TransactionController@allTermTransaction',
			'as'=>'alltermtransaction'
		])->where('id','[0-9]+');
	    //Add transaction to contractor

		//update transaction
		Route::put('update/{id}',[
			'uses'=>'TransactionController@update',
			'as'=>'updatetransaction'
		])->where('id','[0-9]+');
		//delete transaction
		Route::get('delete/{id}',[
			'uses'=>'TransactionController@destroy',
			'as'=>'deletetransaction'
		])->where('id','[0-9]+');
	});
  //Routes to manipulate notes within terms
  Route::group(['prefix'=>'notes'],function(){
    //add Notes
    Route::get('add/{id}',[
      'uses'=>'NoteController@create',
      'as'=>'addnote'
    ])->where('id','[0-9]+');
    Route::post('add/{id}',[
      'uses'=>'NoteController@store',
      'as'=>'addnote'
    ])->where('id','[0-9]+');
    //update Notes
    Route::get('update/{id}',[
      'uses'=>'NoteController@edit',
      'as'=>'updatenote'
    ])->where('id','[0-9]+');
    Route::put('update/{id}',[
      'uses'=>'NoteController@update',
      'as'=>'updatenote'
    ])->where('id','[0-9]+');
    //show all Notes
    Route::get('all/{id}',[
      'uses'=>'NoteController@index',
      'as'=>'allnote'
    ])->where('id','[0-9]+');
    //delete note
    Route::get('delete/{id}',[
      'uses'=>'NoteController@destroy',
      'as'=>'deletenote'
    ])->where('id','[0-9]+');
  });
  //Routes to manipulate Papers within Projects
  Route::group(['prefix'=>'document'],function(){
    //add Papers
    Route::get('add/{id}',[
      'uses'=>'PaperController@create',
      'as'=>'addpaper'
    ])->where('id','[0-9]+');
    Route::post('add/{id}',[
      'uses'=>'PaperController@store',
      'as'=>'addpaper'
    ])->where('id','[0-9]+');
    //update Papers
    Route::get('update/{id}',[
      'uses'=>'PaperController@edit',
      'as'=>'updatepaper'
    ])->where('id','[0-9]+');
    Route::put('update/{id}',[
      'uses'=>'PaperController@update',
      'as'=>'updatepaper'
    ])->where('id','[0-9]+');
    //show all Papers
    Route::get('all/{id}',[
      'uses'=>'PaperController@index',
      'as'=>'allpaper'
    ])->where('id','[0-9]+');
    //show Paper
    Route::get('show/{id}',[
      'uses'=>'PaperController@show',
      'as'=>'showpaper'
    ])->where('id','[0-9]+');
    //show Paper PDF
    Route::get('show/PDF/{fileName}/{ext}',[
      'uses'=>'PaperController@showFile',
      'as'=>'showPaperPdf'
    ]);
    //delete Paper
    Route::delete('delete/{id}',[
      'uses'=>'PaperController@destroy',
      'as'=>'deletepaper'
    ])->where('id','[0-9]+');
  });

});
