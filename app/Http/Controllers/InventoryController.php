<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Inventory;
use App\Project;
use App\Log;
use Validator;
use Auth;
use Storage;
use File;
class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
      $project = Project::where('id',$id)->where('deleted',0)->firstOrFail();
      $inventories = $project->inventories()->where('deleted',0)->orderBy('created_at','desc')->paginate(30);
      $data = [
        'active' => 'project',
        'project' => $project,
        'inventories' => $inventory
      ];
      return view('inventory.all',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
      $project = Project::where('id',$id)->where('deleted',0)->firstOrFail();
      $data = [
        'active' => 'project',
        'project' => $project
      ];
      return view('inventory.add',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $id)
    {
      $project = Project::findOrFail($id);
      $rules = [
        'name' => 'required',
        'file' => 'required|file|max:20000|mimetypes:application/csv,application/excel,application/pdf,application/vnd.ms-excel,application/vnd.msexcel,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      ];
      $error_messages = [
        'name.required' => 'من فضلك أدخل أسم الملف',
        'file.required' => 'من فضلك أختار ملف الحصر',
        'file.file' => 'ملف الحصر يجب أن يكون ملف',
        'file.max' => 'مساحة الملف يجب أن لا تتعدى ال 20 ميجابايت',
        'file.mimetypes' => 'نوع الملف يجب أن يكون ملف excel أو PDF فقط',
      ];
      $validator = Validator::make($req->all(),$rules,$error_messages);
       if ($validator->fails()) {
       	return redirect()->back()->withErrors($validator)->withInput();
      }
      $fileName='inventory_'.$id.'_'.time().'.'.$req->file('file')->getClientOriginalExtension();

      try{
				DB::beginTransaction();
				Storage::disk('inventory')->put($fileName,File::get($req->file('file')));
        $inventory = new Inventory;
				$inventory->name = $req->input('name');
				$inventory->description = $req->input('description')??null;
				$inventory->file = $fileName;
				$inventory->project_id = $id;
				$saved = $inventory->save();

				$log = new Log;
				$log->table = "inventory";
				$log->action = "create";
				$log->record_id = $inventory->id;
				$log->user_id = Auth::user()->id;
				$log->description = "قام بأضافة ملف رسم بمشروع ".$project->name;
				$log->save();
				DB::commit();
			}catch(Exception $e){
				DB::rollBack();
        return redirect()->back()->with("insert_error","حدث عطل خلال حفظ ملف الحصر , من فضلك حاول مرة أخرى");
			}
			return redirect()->route('showproject',$id)->with('success','تم أضافة ملف الحصر بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $inventory = Inventory::where('id',$id)->where('deleted',0)->firstOrFail();
      $data = [
        'active' => 'project',
        'project' => $inventory->project,
        'inventory' => $inventory
      ];
      return view('inventory.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $inventory = Inventory::where('id',$id)->where('deleted',0)->firstOrFail();
      $data = [
        'active' => 'project',
        'project' => $inventory->project,
        'inventory' => $inventory
      ];
      return view('inventory.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $inventory = Inventory::findOrFail($id);
      $rules = ['name' => 'required'];
      $error_messages = ['name.required' => 'من فضلك أدخل أسم الملف'];
      $validator = Validator::make($req->all(),$rules,$error_messages);
      if($validator->fails()) {
       	return redirect()->back()->withErrors($validator)->withInput();
      }
      $description = "";
      $check = false;
      if ($inventory->name != $req->input("name")) {
        $check = true;
        $description .= "قام بتغيير أسم ملف الحصر من ".$inventory->name." إلى ".$req->input("name").". ";
        $inventory->name = $req->input("name");
      }
      if ($inventory->description != $req->input("description")) {
        $check = true;
        $description .= "قام بتغيير وصف ملف الحصر من '".$inventory->description."' إلى '".$req->input("description")."'. ";
        $inventory->description = $req->input("description");
      }
      if ($check) {
        $saved = $inventory->save();
        //check if saved correctly
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال تعديل ملف الحصر , من فضلك حاول مرة أخرى");
        }
        $log=new Log;
        $log->table="inventories";
        $log->action="update";
        $log->record_id=$id;
        $log->user_id=Auth::user()->id;
        $log->description=$description;
        $log->save();
        return redirect()->back()->with("success","تم تعديل ملف الحصر بنجاح");
      }
      return redirect()->back()->with("info","لا يوجد تعديل حتى يتم تعديله");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
