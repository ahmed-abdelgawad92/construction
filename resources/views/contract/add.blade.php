@extends('layouts.master')
@section('title',"أنشاء عقد")
@section('content')
<div class="content">
   <div class="row">
     @if (count($termTypeContractors)>0||count($ContractorsWithoutTermType)>0)
     <div class="col-10 offset-1">
       <div class="panel panel-default">
         <div class="panel-heading">
           <h3>أنشاء عقد للبند <a href="{{route("showterm",['id'=>$term->id])}}">{{$term->code}}</a> بمشروع <a href="{{route("showproject",['id'=>$term->project_id])}}">{{$term->project->name}}</a></h3>
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
         <form action="{{route("addcontract",['id'=>$term->id])}}" method="post" id="add_contract">
           <div class="form-group row @if($errors->has("contractor_id")) has-error @endif">
              <label for="contractor_id" class="control-label col-sm-2 col-md-2 col-lg-2">المقاول *</label>
              <div class="col-sm-10 col-md-10 col-lg-10">
                <div class="input-group">
                  <input type="text" name="contractor_details" id="show_contractor_details" autocomplete="off" class="form-control readonly" readonly  placeholder="أدخل المقاول" value="{{old("contractor_details")}}">
                  <input type="hidden" name="contractor_id" id="contractor_id" value="{{old("contractor_id")}}">
                  <span class="input-group-addon" id="basic-addon1">أختار</span>
                </div>
                @if($errors->has("contractor_id"))
                  @foreach($errors->get("contractor_id") as $error)
                    <span class="help-block">{{ $error }}</span>
                  @endforeach
                @endif
              </div>
           </div>
           <div class="form-group row @if($errors->has("contract_text")) has-error @endif">
              <label for="contract_text" class="control-label col-sm-2 col-md-2 col-lg-2">نص العقد</label>
              <div class="col-sm-10 col-md-10 col-lg-10">
                <textarea name="contract_text" id="contract_text" class="form-control" placeholder="أدخل نص العقد">{{old("contract_text")}}</textarea>
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
                  <input type="text" name="unit_price" id="unit_price" autocomplete="off" class="form-control" placeholder="أدخل سعر الوحدة" value="{{old("unit_price")}}">
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
                <input type="text" autocomplete="off" name="started_at" id="started_at" class="form-control" placeholder="أدخل تاريخ بداية العمل , أذا تركته فارغاً , تاريخ اليوم سيصبح قيمته" value="{{old("started_at")}}">
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
         </form>
         </div>
      </div>
    </div>
  </div>
</div>
<div id="float_container">
	<div id="float_form_container">
		<span class="close">&times;</span>
		<h3 class="center">أختار المقاول</h3>
    @if (count($termTypeContractors)>0)
    <div class="category">
      <h5 class="category">{{$term->type}}</h5>
    </div>
    <div>
    @foreach ($termTypeContractors as $contractor)
      <div class="contractor_select" data-id="{{$contractor->id}}" data-name="{{$contractor->name}}" data-type="{{str_replace(","," , ",$contractor->type)}}" data-phone="{{str_replace(","," , ",$contractor->phone)}}" data-city="{{$contractor->city}}">
        <div class="row">
          <div class="col-2">
            <img src="{{asset("images/contractor.png")}}" class="w-100" alt="">
          </div>
          <div class="col-10">
            <h4>{{$contractor->name}}</h4>
            {{str_replace(","," , ",$contractor->phone)}}&nbsp;&nbsp;&nbsp;&nbsp;
            {{$contractor->city}}&nbsp;&nbsp;&nbsp;&nbsp;
            {{str_replace(","," , ",$contractor->type)}}
          </div>
        </div>
      </div>
    @endforeach
    </div>
    @endif
    @if (count($ContractorsWithoutTermType)>0)
    <div class="category">
      <h5 class="category">مقاولين من أنواع آخرى</h5>
    </div>
    <div>
    @foreach ($ContractorsWithoutTermType as $contractor)
      <div class="contractor_select" data-id="{{$contractor->id}}" data-name="{{$contractor->name}}" data-type="{{str_replace(","," , ",$contractor->type)}}" data-phone="{{str_replace(","," , ",$contractor->phone)}}" data-city="{{$contractor->city}}">
        <div class="row">
          <div class="col-2">
            <img src="{{asset("images/contractor.png")}}" class="w-100" alt="">
          </div>
          <div class="col-10">
            <h4>{{$contractor->name}}</h4>
            {{str_replace(","," , ",$contractor->phone)}}&nbsp;&nbsp;&nbsp;&nbsp;
            {{$contractor->city}}&nbsp;&nbsp;&nbsp;&nbsp;
            {{str_replace(","," , ",$contractor->type)}}
          </div>
        </div>
      </div>
    @endforeach
    </div>
    @endif
	</div>
</div>
@else
<div class="alert alert-warning">لا يوجد مقاولون <a href="" class="btn btn-warning">اضافة مقاول</a></div>
@endif
@endsection
