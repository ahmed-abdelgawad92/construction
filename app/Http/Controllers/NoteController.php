<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Term;
use App\Note;
use App\Log;

use Auth;
use Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
      $term= Term::where('id',$id)->where('deleted',0)->firstOrFail();
      $notes = $term->notes()->where('notes.deleted',0)->orderBy('notes.created_at','desc')->get();
      $array = [
        'term'=>$term,
        'notes'=>$notes,
        'active'=>'term'
      ];
      return view('note.all',$array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
      $term= Term::where('id',$id)->where('deleted',0)->firstOrFail();
      $array=[
        'active'=>'term',
        'term'=>$term
      ];
      return view("note.add",$array);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $id){
        //rules
        $rules=[
          "title"=>"required",
          "note"=>"required"
        ];
        //error_messages
        $error_messages=[
          'title.required'=>'من فضلك أدخل العنوان',
          'note.required'=>'من فضلك أدخل الملحوظة'
        ];
        //validate
        $validator=Validator::make($req->all(),$rules,$error_messages);
        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
        }

        //store Note
        $term=Term::findOrFail($id);
        $note = new Note;
        $note->title=$req->input("title");
        $note->note=$req->input("note");
        $note->term_id=$term->id;
        $saved= $note->save();

        //check if saved correctly
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال حفظ هذه الملحوظة , من فضلك حاول مرة اخرى في وقت لاحق");
        }
        $log = new Log;
        $log->table="notes";
        $log->action="create";
        $log->record_id=$note->id;
        $log->description="قام بأضافة ملحوظة بالبند";
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with('success',"تم أضافة الملحوظة بنجاح");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note=Note::where('id',$id)->where('deleted',0)->firstOrFail();
        $term = $note->term;
        $array=[
          'active'=>'term',
          'note'=>$note,
          'term'=>$term
        ];
        return view("note.edit",$array);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
      //rules
      $rules=[
        "title"=>"required",
        "note"=>"required"
      ];
      //error_messages
      $error_messages=[
        'title.required'=>'من فضلك أدخل العنوان',
        'note.required'=>'من فضلك أدخل الملحوظة'
      ];
      //validate
      $validator=Validator::make($req->all(),$rules,$error_messages);
      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $description="";
      $check=false;
      //store Note
      $note = Note::findOrFail($id);
      if ($note->title!=$req->input("title")) {
        $description.='قام بتغيير العنوان من "'.$note->title.'" إلى "'.$req->input("title").'" .';
        $check=true;
        $note->title=$req->input("title");
      }
      if ($note->note!=$req->input("note")) {
        $description.='قام بتغيير الملحوظة من "'.$note->note.'" إلى "'.$req->input("note").'" .';
        $check=true;
        $note->note=$req->input("note");
      }
      if ($check) {
        $saved= $note->save();
        //check if saved correctly
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال تعديل هذه الملحوظة , من فضلك حاول مرة اخرى في وقت لاحق");
        }
        $log = new Log;
        $log->table="notes";
        $log->action="update";
        $log->record_id=$note->id;
        $log->description=$description;
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with('success',"تم تعديل الملحوظة بنجاح");
      }
      return redirect()->back()->with('info',"لا يوجد تعديل حتى يتم تعديله");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $note = Note::findOrFail($id);
      if ($note->deleted==0) {
        $note->deleted=1;
        $saved=$note->save();
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال حفظ هذه الملحوظة , من فضلك حاول مرة اخرى في وقت لاحق");
        }
        $log = new Log;
        $log->table="notes";
        $log->record_id=$note->id;
        $log->action="delete";
        $log->user_id=Auth::user()->id;
        $log->description="قام بحذف هذه الملحوظة";
        $log->save();
        return redirect()->back()->with('success',"تم حذف الملحوظة بنجاح");
      }
      return redirect()->back()->with('success',"الملحوظة محذوفة بالفعل");

    }
}
