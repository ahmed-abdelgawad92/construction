<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Log;
use Auth;
class LogController extends Controller {

	private $table_names =[
		"organizations",
		"projects",
		"terms",
		"productions",
		"notes",
		"contracts",
		"term_types",
		"suppliers",
		"contractors",
		"stores",
		"consumptions",
		"graphs",
		"expenses",
		"papers",
		"company_employees",
		"employees",
		"transactions",
		"payments",
		"advances",
		"users",
		"store_types",
		"taxes"
	];
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Auth::user()->privilege > 1) {
			$logs = [];
			foreach ($this->table_names as $table) {
				$logs[$table] = Log::where('table',$table)->where('deleted',0)->orderBy('created_at','desc')->take(5)->get();
			}
			return view('log.lastFive',['active'=>'user','logs'=>$logs]);
		} else {
			return view('errors.privilege');
		}
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexOfTable($table)
	{
		if (Auth::user()->privilege > 1) {
			$logs = Log::where('table',$table)->where('deleted',0)->orderBy('created_at','desc')->paginate(30);
			return view('log.all',['active'=>'user','logs'=>$logs]);
		} else {
			return view('errors.privilege');
		}
	}


		// foreach ($logs as $key => $values) {
		// 	echo "<h3>$key</h3>";
		// 	if (count($values) == 0) {
		// 		echo "A7A <br>";
		// 		continue;
		// 	}
		// 	foreach ($values as $value) {
		// 		echo "$value->id $value->table_name $value->description <br>";
		// 	}
		// }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
