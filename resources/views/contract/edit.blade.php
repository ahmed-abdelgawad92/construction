@extends('layouts.master')
@section('title',"أنشاء عقد")
@section('content')
<div class="content">
   <div class="row">
     <div class="col-10 offset-1">
       <div class="panel panel-default">
         <div class="panel-heading">
           <h3>تعديل عقد للبند <a href="{{route("showterm",['id'=>$contract->term_id])}}">{{$contract->term->code}}</a> بمشروع <a href="{{route("showproject",['id'=>$contract->term->project_id])}}">{{$contract->term->project->name}}</a> مع المقاول <a href="{{route('showcontractor',$contract->contractor_id)}}">{{$contract->contractor->name}}</a></h3>
         </div>
         <div class="panel-body">
         @if(session("success"))
           <div class="alert alert-success">
             <strong>{{ session("success") }}</strong>
           </div>
         @endif
         @if(session("insert_error"))
           <div class="alert alert-danger">
             <strong>{{ session("insert_error") }}</strong>
           </div>
         @endif
         @if(session("info"))
           <div class="alert alert-info">
             <strong>{{ session("info") }}</strong>
           </div>
         @endif
         <form action="{{route("updatecontract",['id'=>$contract->id])}}" method="post" id="add_contract">
           <div class="form-group row @if($errors->has("contract_text")) has-error @endif">
              <label for="contract_text" class="control-label col-sm-2 col-md-2 col-lg-2">نص العقد</label>
              <div class="col-sm-10 col-md-10 col-lg-10">
                <textarea name="contract_text" id="contract_text" class="form-control" placeholder="أدخل نص العقد">{{$contract->contract_text}}</textarea>
                @if($errors->has("contract_text"))
                  @foreach($errors->get("contract_text") as $error)
                    <span class="help-block">{{ $error }}</span>
                  @endforeach
                @endif
              </div>
           </div>
           <div class="form-group row @if($errors->has("unit_price")) has-error @endif">
              <label for="unit_price" class="control-label col-sm-2 col-md-2 col-lg-2">سعر الوحدة *</label>
              <div class="col-sm-10 col-md-10 col-lg-10">
                <div class="input-group">
                  <input type="text" name="unit_price" id="unit_price" autocomplete="off" class="form-control" placeholder="أدخل سعر الوحدة" value="{{$contract->unit_price}}">
                  <span class="input-group-addon" id="basic-addon1">جنيه</span>
                </div>
                @if($errors->has("unit_price"))
                  @foreach($errors->get("unit_price") as $error)
                    <span class="help-block">{{ $error }}</span>
                  @endforeach
                @endif
              </div>
           </div>
           <div class="form-group row @if($errors->has("started_at")) has-error @endif">
              <label for="started_at" class="control-label col-sm-2 col-md-2 col-lg-2">تاريخ بداية العمل</label>
              <div class="col-sm-10 col-md-10 col-lg-10">
                <input type="text" autocomplete="off" name="started_at" id="started_at" class="form-control" placeholder="أدخل تاريخ بداية العمل , أذا تركته فارغاً , تاريخ اليوم سيصبح قيمته" value="{{date("Y-m-d",strtotime($contract->started_at))}}">
                @if($errors->has("started_at"))
                  @foreach($errors->get("started_at") as $error)
                    <span class="help-block">{{ $error }}</span>
                  @endforeach
                @endif
              </div>
           </div>
           <div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
            	<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
            </div>
           {{ csrf_field() }}
           @method("PUT")
         </form>
         </div>
      </div>
    </div>
  </div>
</div>
@endsection
