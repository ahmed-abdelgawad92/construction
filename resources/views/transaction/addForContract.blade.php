@extends('layouts.master')
@section('title','أضافة مستخلص للمقاول '.$contract->contractor->name)
@section('content')
<div class="content">
   <div class="panel panel-default">
     <div class="panel-heading">
       <h3>أضافة مستخلص إلى المقاول <a href="{{route('showcontractor',$contract->contractor_id)}}">{{$contract->contractor->name}}</a> بالبند <a href="{{route('showterm',['id'=>$contract->term->id])}}">{{$contract->term->code}}</a> بمشروع <a href="{{route('showproject',['id'=>$contract->term->project_id])}}">{{$contract->term->project->name}}</a></h3>
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
     <form action="{{route('addcontracttransaction',['id'=>$contract->id])}}" method="post" id="add_contract_transaction">
       <div class="form-group row">
         <label class="control-label col-sm-2 col-md-2 col-lg-2">أجمالى المبالغ المدفوعة للمقاول</label>
         <label class="control-label col-sm-10 col-md-10 col-lg-10">{{$prev_production*$contract->unit_price}} جنيه</label>
       </div>
       <div class="form-group row">
          <label class="control-label col-sm-2 col-md-2 col-lg-2">قيمة الوحدة للمقاول</label>
          <label class="control-label col-sm-10 col-md-10 col-lg-10" id="unit_price" data-value="{{$contract->unit_price}}">{{$contract->unit_price}} جنيه</label>
       </div>
       <div class="form-group row">
          <label class="control-label col-sm-2 col-md-2 col-lg-2">الكمية السابقة المُنتجة</label>
          <label class="control-label col-sm-10 col-md-10 col-lg-10" id="prev_production" data-prev-amount="{{$prev_production}}" data-value="{{$prev_production*$contract->term->value}}">{{$prev_production}}</label>
       </div>
       <div class="form-group row @if($errors->has("current_production")) has-error @endif">
          <label for="current_production" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية الحالية المُنتجة</label>
          <div class="col-sm-10 col-md-8 col-lg-8">
            <div class="input-group">
              <input type="text" name="current_production" id="current_production" autocomplete="off" class="form-control number" placeholder="أدخل الكمية الحالية المُنتجة" value="{{old('current_production')??$current_production}}">
              <span class="input-group-addon" id="basic-addon1">{{$contract->term->unit}}</span>
            </div>
            @if($errors->has("current_production"))
              @foreach($errors->get("current_production") as $error)
                <span class="help-block">{{ $error }}</span>
              @endforeach
            @endif
          </div>
       </div>
       <div class="form-group row">
          <label class="control-label col-sm-2 col-md-2 col-lg-2">أجمالى الكمية المُنتجة</label>
          <label class="control-label col-sm-10 col-md-10 col-lg-10" id="total_production">{{$total_production}}</label>
       </div>
       <div class="form-group row">
          <label class="control-label col-sm-2 col-md-2 col-lg-2">المبلغ الذى سيتم دفعه حسب الكمية الحالية</label>
          <label class="control-label col-sm-10 col-md-10 col-lg-10" id="current_value">{{$current_production*$contract->unit_price}} جنيه</label>
       </div>
       <div class="form-group row @if($errors->has('payment_type')) has-error @endif">
         <label for="payment_type" class="control-label col-sm-2 col-md-2 col-lg-2">طريقة الدفع</label>
         <div class="col-sm-8 col-md-8 col-lg-8">
           <label><input type="radio" name="payment_type" value="0" @if(!old("paymen_type")||old("paymen_type")==0) checked @endif> صندوق</label>
           <label><input type="radio" name="payment_type" value="1" @if(old("paymen_type")==1) checked @endif> قرض</label>
           @if($errors->has('payment_type'))
             @foreach($errors->get('payment_type') as $error)
               <span class="help-block">{{ $error }}</span>
             @endforeach
           @endif
         </div>
       </div>
       <div class="center">
        	<button class="btn btn-primary" style="width:150px" id="save_btn">حفظ</button>
        </div>
        @csrf
     </form>
     </div>
  </div>
</div>

@endsection
