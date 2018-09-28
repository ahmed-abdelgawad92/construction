<?php

namespace App\Http\Controllers;

use App\Log;
use App\Term;
use App\Contract;
use App\Contractor;
use Illuminate\Http\Request;
use Auth;
use Validator;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromTerm($id)
    {
        $term = Term::where("id",$id)->where('deleted',0)->firstOrfail();
        $termTypeContractors = Contractor::where("type","like","%".$term->type."%")
                                  ->where("deleted",0)
                                  ->orderBy("name","asc")
                                  ->get();
        $ContractorsWithoutTermType = Contractor::where("type","not like","%".$term->type."%")
                                  ->where("deleted",0)
                                  ->orderBy("name","asc")
                                  ->get();
        $array=[
          'active'=>'term',
          'term'=>$term,
          'termTypeContractors'=>$termTypeContractors,
          'ContractorsWithoutTermType'=>$ContractorsWithoutTermType
        ];
        return view("contract.add",$array);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req,$id)
    {
        $rules=[
          'contractor_id'=>'required|exists:contractors,id',
          'contract_text'=>'required',
          'unit_price'=>'required|numeric',
          'started_at'=>'nullable|date'
        ];
        $error_messages=[
          'contractor_id.required'=>'من فضلك أختار المقاول ',
          'contractor_id.exists'=>'المقاول الذى تم أختياره ’ لا يوجد له بيانات بقاعدة البيانات',
          'contract_text.required'=>'يجب كتابة نص العقد',
          'unit_price.required'=>'يجب كتابة سعر الوحدة للمقاول',
          'unit_price.numeric'=>'سعر الوحدة يجب أن يتكون من أرقام فقط',
          'started_at.date'=>'تاريخ البداية يجب أن يكون تاريخ صحيح'
        ];
        $validator = Validator::make($req->all(),$rules,$error_messages);
        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
        }
        $term = Term::findOrFail($id);
        if($term->done==1 || $term->deleted==1){
          return redirect()->back()->with("insert_error","عذراً لا يمكن انشاء عقد إلى بند مٌنتهى او محذوف")->withInput();
        }
        $checkContract = Contract::where("term_id",$term->id)->where("contractor_id",$req->input("contractor_id"))->where("deleted",0)->count();
        if ($checkContract>0) {
          return redirect()->back()->with("insert_error","هذا المقاول لديه بالفعل عقد مع هذا البند , تفضل بتعديل العقد من خلال صفحة البند اذا كنت ترغب اذا كنت ترغب")->withInput();
        }
        $contract = new Contract;
        $contract->term_id=$term->id;
        $contract->contractor_id=$req->input("contractor_id");
        $contract->contract_text=$req->input("contract_text");
        $contract->unit_price=$req->input("unit_price");
        $contract->started_at= (!empty($req->input("started_at")))? $req->input("started_at") : date("Y-m-d");
        $saved=$contract->save();
        //check if saved correctly
        if (!$saved) {
          return redirect()->back()->with("insert_error","حدث عطل خلال حفظ هذ العقد, من فضلك حاول مرة اخرى");
        }
        $log=new Log;
        $log->table="contracts";
        $log->action="create";
        $log->record_id=$contract->id;
        $log->user_id=Auth::user()->id;
        $log->description="قام بانشاء هذا العقد ";
        $log->save();
        return redirect()->route("showterm",['id'=>$term->id])->with("success","تم أنشاء العقد بنجاح");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
