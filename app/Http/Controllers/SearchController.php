<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Project;
use App\Term;
use App\Contractor;
use App\Supplier;
use App\Employee;
use App\CompanyEmployee;
use App\User;
use Auth;
use Str;
use Validator;
class SearchController extends Controller
{
  private const FUNCTION_LIST = [
    "searchOrganization",
    "searchProject",
    "searchTerm",
    "searchContractor",
    "searchSupplier",
    "searchEmployee",
    "searchCompanyEmployee",
    "searchUser"
  ];
  /*
  * @param $req ['Table', 'search']
  * return Collections
  *
  */
  public function search(Request $req)
  {
    $rules = [
      'table' => 'required|between:0,6',
      'search' => 'required'
    ];
    $error_messages = [
      'table.required' => 'من فضلك أختار تصنيف البحث',
      'table.between' => 'من فضلك أختار تصنيف البحث',
      'search.required' => 'من فضلك أدخل الكلمات أو الأكواد المراد البحث عنها'
    ];
    $validator = Validator::make($req->all(),$rules,$error_messages);
     if ($validator->fails()) {
     	return redirect()->back()->withErrors($validator)->withInput();
    }
    $search = Str::arabic_replace($req->input("search"));
    $searchFunction = $this::FUNCTION_LIST[$req->input("table")];
    $records = $this->$searchFunction($search);
    $data = [
      'records' => $records,
      'search' => $search,
      'table' => $searchFunction
    ];
    return view('search',$data);
  }
  //search Organization
  public function searchOrganization($search){
    $records = Organization::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
  //search Project
  public function searchProject($search){
    $records = Project::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
  //search Term
  public function searchTerm($search){
    $records = Term::where(function($query) use ($search){
      $query->where('code','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('code','like','%'.$searchWord.'%');
      }
    })->where('deleted',0)->orderBy('code','asc')->paginate(30);
    return $records;
  }
  //search Contractor
  public function searchContractor($search){
    $records = Contractor::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%')
      ->orWhere('type','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%')
        ->orWhere('type','like','%'.$search.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
  //search Supplier
  public function searchSupplier($search){
    $records = Supplier::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%')
      ->orWhere('type','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%')
        ->orWhere('type','like','%'.$search.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
  //search Employee
  public function searchEmployee($search){
    $records = Employee::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%')
      ->orWhere('job','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%')
        ->orWhere('job','like','%'.$search.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
  //search CompanyEmployee
  public function searchCompanyEmployee($search){
    $records = CompanyEmployee::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%')
      ->orWhere('job','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%')
        ->orWhere('job','like','%'.$search.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
  //search User
  public function searchUser($search){
    $records = User::where(function($query) use ($search){
      $query->where('name','like','%'.$search.'%')
      ->orWhere('username','like','%'.$search.'%');
      $arr = explode(" ",$search);
      foreach ($arr as $searchWord) {
        $query->orWhere('name','like','%'.$searchWord.'%')
        ->orWhere('username','like','%'.$search.'%');
      }
    })->where('deleted',0)->orderBy('name','asc')->paginate(30);
    return $records;
  }
}
