<?php

namespace App\Http\Controllers;

use App\Paper;
use App\Project;
use App\Log;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Validator;
use Storage;
use Auth;
use File;

class PaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
      $project = Project::findOrFail($id);
      $papers = $project->papers()->where("deleted",0)->orderBy("papers.created_at","desc")->get();
      $array=[
        'project'=>$project,
        'papers'=>$papers,
        'active'=>'project'
      ];
      return view("paper.all",$array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
      $project = Project::findOrFail($id);
      $array = [
        'active'=> 'project',
        'project'=>$project
      ];
      return view("paper.add",$array);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $id)
    {
      //rules
      $rules=[
        'name'=>'required',
        'description'=>'nullable',
        'path'=>'required|file|mimes:pdf,jpeg,jpg,png,bmp,gif'
      ];
      //error_messages
      $error_messages=[
        'name.required'=>'من فضلك أدخل أسم الورقية',
        'path.required'=>'من فضلك أختار ملف الورقية',
        'path.file'=>'لابد من أدخال ملف صحيح',
        'path.mimes'=>'نوع الملف يجب أن يكون .pdf , .jpeg , .jpg , .png , .bmp أو .gif',
      ];
      //validate
      $validator = Validator::make($req->all(),$rules,$error_messages);
       if ($validator->fails()) {
       	return redirect()->back()->withErrors($validator)->withInput();
      }
      //store
      $project = Project::findOrFail($id);
      $paper = new Paper;
      try {
        $fileName='document_'.$project->id.'_'.time().'.'.$req->file("path")->getClientOriginalExtension();
        Storage::disk('paper')->put($fileName,File::get($req->file('path')));
        $paper->name = $req->input("name");
        $paper->description = $req->input("description");
        $paper->path = $fileName;
        $paper->project_id = $project->id;
        $saved = $paper->save();
      } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with("insert_error","حدث عطل خلال حفظ الورقية ,من فضلك حاول مرة أخرى");
      }

      //create log
      $log=new Log;
      $log->table="papers";
      $log->action="create";
      $log->record_id=$paper->id;
      $log->user_id=Auth::user()->id;
      $log->description="قام بأضافة ورقية جديدة إلى المشروع ".$project->name." , أسم الورقية : ".$paper->name;
      $log->save();
      return redirect()->route("allpaper",["id"=>$project->id])->with("success","تم حفظ الورقية بنجاح إلى المشروع ".$project->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $paper = Paper::where("id",$id)->where('deleted',0)->firstOrFail();
      $arr = explode('.',$paper->path);
      $ext = strtolower(array_pop($arr));
      $file=Storage::disk('paper')->get($paper->path);
      Storage::disk('public')->put('file.'.$ext, $file);
      $array=[
        'project'=>$paper->project,
        'active'=>'project',
        'paper'=>$paper,
      ];
      return view("paper.show",$array);
    }

    public function showFile($fileName,$ext)
  	{
  		if(Auth::user()->type=='admin'){
  			$file=Storage::disk('paper')->get($fileName);
        switch ($ext) {
          case 'pdf':
            return (new Response($file,200))->header('content-type','application/pdf');
            break;
          case 'png':
            return (new Response($file,200))->header('content-type','image/png');
            break;
          case 'jpg':
            return (new Response($file,200))->header('content-type','image/jpeg');
            break;
          case 'jpeg':
            return (new Response($file,200))->header('content-type','image/jpeg');
            break;
          case 'gif':
            return (new Response($file,200))->header('content-type','image/gif');
            break;
          case 'bmp':
            return (new Response($file,200))->header('content-type','image/bmp');
            break;
          default :
            abort("404");
            break;
        }
  		}else
  			abort('404');
  	}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $paper = Paper::findOrFail($id);
      $array=[
        'active'=>'project',
        'paper'=>$paper,
        'project'=>$paper->project
      ];
      return view('paper.edit',$array);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
      //rules
      $rules=[
        'name'=>'required',
        'description'=>'nullable'
      ];
      //error_messages
      $error_messages=[
        'name.required'=>'من فضلك أدخل أسم الورقية'
      ];
      //validate
      $validator = Validator::make($req->all(),$rules,$error_messages);
       if ($validator->fails()) {
       	return redirect()->back()->withErrors($validator)->withInput();
      }
      $paper = Paper::findOrFail($id);
      $check = false;
      $description="";
      if ($paper->name!=$req->input("name")) {
        $check=true;
        $description.="قام بتغيير أسم الورقية من (".$paper->name.") إلى (".$req->input("name").") . ";
        $paper->name = $req->input("name");
      }
      if ($paper->description!=$req->input("description")) {
        $check=true;
        $description.="قام بتغيير وصف الورقية من (".$paper->description.") إلى (".$req->input("description").") . ";
        $paper->description = $req->input("description");
      }
      if($check){
        $saved = $paper->save();
        //check if saved correctly
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال تعديل هذه الورقية , من فضلك حاول مرة أخرى.");
        }
        $log=new Log;
        $log->table="papers";
        $log->action="update";
        $log->record_id=$paper->id;
        $log->user_id=Auth::user()->id;
        $log->description=$description;
        $log->save();
        return redirect()->back()->with("success","تم تعديل الورقية بنجاح");
      }
      return redirect()->back()->with("info","لا يوجد تعديل حتى يتم حفظه");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $paper = Paper::findOrFail($id);
      if ($paper->deleted == 0) {
        $paper->deleted=1;
        $deleted = $paper->save();
        //check if saved correctly
        if (!$deleted) {
          return redirect()->back()->with("insert_error","حدث عطل خلال حذف هذه الورقية");
        }
        $log=new Log;
        $log->table="papers";
        $log->action="delete";
        $log->record_id=$paper->id;
        $log->user_id=Auth::user()->id;
        $log->description="قام بحذف الورقية ".$paper->name." من مشروع ".$paper->project->name;
        $log->save();
        return redirect()->route("allpaper",["id"=>$paper->project_id])->with("success","تم حذف الورقية بنجاح");
      }
      return redirect()->route("showproject",["id"=>$paper->project_id])->with("info","لا يوجد ورقية حتى يتم حذفها");
    }
}
