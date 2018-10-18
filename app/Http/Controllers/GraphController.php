<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Graph;
use App\Project;
use App\Log;

use Auth;
use Validator;
use Storage;
use File;
class GraphController extends Controller {
	/**
	 * Display a listing of all projects.
	 *
	 * @return Response
	 */
	public function chooseProject()
	{
		if(Auth::user()->type=='admin'){
			$projects=Project::where('done',0)->get();
			$array=['active'=>'graph','projects'=>$projects];
			return view('production.allprojects',$array);
		}
		else
			abort('404');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		if(Auth::user()->type=='admin'){
			$project=Project::findOrFail($id);
			$graphs=$project->graphs;
			$array=[
				'active'=>'graph',
				'graphs'=>$graphs,
				'project'=>$project
			];
			return view('graph.all',$array);
		}
		else
			abort('404');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id=null)
	{
		if(Auth::user()->type=='admin'){
			if($id!=null){
				$project=Project::findOrFail($id);
				$array=['active'=>'graph','project'=>$project];
				return view('graph.add',$array);
			}
			else{
				$projects=Project::where('done',0)->get();
				$array=['active'=>'graph','projects'=>$projects];
				return view('graph.add',$array);
			}
		}
		else
			abort('404');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $req)
	{
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'project_id'=>'required|exists:projects,id',
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'type'=>'required|in:0,1',
				'graph'=>'required|file|mimes:pdf|max:15360'
			];
			//errors
			$error_messages=[
				'project_id.required'=>'يجب أختيار المشروع',
				'project_id.exists'=>'المشروع يجب أن يكون موجود بقاعدة البيانات',
				'name.required'=>'يجب أدخال أسم الرسم',
				'name.regex'=>'أسم الرسم يجب أن يتكون من حروف و أرقام و مسافات فقط',
				'graph.required'=>'يجب أختيار الملف المراد رفعه',
				'graph.file'=>'حدث عطل خلال رفع هذا الملف, حاول مرة أخرى',
				'graph.mimes'=>'نوع الملف يجب أن يكون PDF',
				'graph.max'=>'حجم الملف لا يمكن أن يكون أكبر من 15 ميجا بايت'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			$fileName='graph_'.$req->input('project_id').'_'.time().'.pdf';
			try{
				DB::beginTransaction();
				$graph=new Graph;
				$graph->name=$req->input('name');
				$graph->type=$req->input('type');
				$graph->project_id=$req->input('project_id');
				$graph->path=$fileName;
				$saved=$graph->save();
				Storage::disk('graph')->put($fileName,File::get($req->file('graph')));
				$log=new Log;
				$log->table="graphs";
				$log->action="create";
				$log->record_id=$graph->id;
				$log->user_id=Auth::user()->id;
				$log->description="قام بأنشاء رسم جديد";
				$log->save();
				DB::commit();
			}catch(Exception $e){
				DB::rollBack();
				return redirect()->back()->with('insert_error','حدث عطل خلال أضافة هذا الرسم, يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showgraph',$graph->id)->with('success','تم أضافة الرسم بنجاح');
		}else
			abort('404');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::user()->type=='admin'){
			$graph=Graph::findOrFail($id);
			$array=['active'=>'graph','graph'=>$graph];
			return view('graph.show',$array);
		}else
			abort('404');
	}

	public function showPdf($fileName)
	{
		if(Auth::user()->type=='admin'){
			$file=Storage::disk('graph')->get($fileName);
			return (new Response($file,200))->header('content-type','application/pdf');
		}else
			abort('404');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(Auth::user()->type=='admin'){
			$graph=Graph::findOrFail($id);
			$array=['active'=>'graph','graph'=>$graph];
			return view('graph.edit',$array);
		}
		else
			abort('404');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
		if(Auth::user()->type=='admin'){
			//validation rules
			$rules=[
				'name'=>'required|regex:/^[\pL\pN\s]+$/u',
				'type'=>'required|in:0,1'
			];
			//errors
			$error_messages=[
				'name.required'=>'يجب أدخال أسم الرسم',
				'name.regex'=>'أسم الرسم يجب أن يتكون من حروف و أرقام و مسافات فقط'
			];
			//validate
			$validator=Validator::make($req->all(),$rules,$error_messages);
			if($validator->fails())
				return redirect()->back()->withErrors($validator)->withInput();
			$graph=Graph::findOrFail($id);
			$check = false;
			$description ="";
			if($graph->name!=$req->input("name")){
				$check = true;
				$description.='قام بتغيير أسم الرسم من "'.$graph->name.'" إلى "'.$req->input("name").'" . ';
				$graph->name=$req->input('name');
			}
			if($graph->type!=$req->input("type")){
				$old = ($graph->type==1)?'رسم معمارى':'رسم أنشائى';
				$new = ($req->input("type")==1)?'رسم معمارى':'رسم أنشائى';
				$check = true;
				$description.='قام بتغيير نوع الرسم من "'.$old.'" إلى "'.$new.'" .';
				$graph->type=$req->input('type');
			}
			if($check){
				$saved=$graph->save();
				if(!$saved){
					return redirect()->back()->with('insert_error','حدث عطل خلال تعديل هذا الرسم, يرجى المحاولة فى وقت لاحق');
				}
				$log=new Log;
				$log->table="graphs";
				$log->action="update";
				$log->record_id=$id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				return redirect()->route('showgraph',$graph->id)->with('success','تم تعديل الرسم بنجاح');
			}
			return redirect()->back()->with("info","لا يوجد تعديل حتى يتم حفظه");
		}
		else
			abort('404');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->type=='admin'){
			$graph=Graph::findOrFail($id);
			$fileName=$graph->path;
			$project_id=$graph->project_id;
			$type = ($graph->type==1)?'المعمارى':'الأنشائى';
			$description ='قام بحذف الرسم '.$type.' صاحب الأسم "'.$graph->name.'" من مشروع "'.$graph->project->name.'".';
			try {
				DB::beginTransaction();
				$graph->delete();
				Storage::disk('graph')->delete($fileName);
				$log=new Log;
				$log->table="graphs";
				$log->action="delete";
				$log->record_id=$project_id;
				$log->user_id=Auth::user()->id;
				$log->description=$description;
				$log->save();
				DB::commit();
			} catch (\Exception $e) {
				DB::rollBack();
				return redirect()->back()->with('delete_error','حدث عطل خلال حذف هذا الرسم , يرجى المحاولة فى وقت لاحق');
			}
			return redirect()->route('showproject',$project_id)->with('success','تم حذف الرسم بنجاح');
		}
		else
			abort('404');
	}

}
