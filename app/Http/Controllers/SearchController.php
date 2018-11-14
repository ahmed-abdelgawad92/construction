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
    $this->$searchFunction($search);
  }
  //search Organization
  public function searchOrganization($search){
    
  }
  //search Project
  public function searchProject($search){

  }
  //search Term
  public function searchTerm($search){

  }
  //search Contractor
  public function searchContractor($search){

  }
  //search User
  public function searchUser($search){

  }
  //search Supplier
  public function searchSupplier($search){

  }
  //search Employee
  public function searchEmployee($search){

  }
  //search CompanyEmployee
  public function searchCompanyEmployee($search){

  }
}
