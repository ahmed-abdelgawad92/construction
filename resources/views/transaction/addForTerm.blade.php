@extends('layouts.master')
@section('title','أضافة مستخلص بالبند '.$term->code)
@section('content')
<div class="content">
   <div class="panel panel-default">
     <div class="panel-heading">
       <h3>أضافة مستخلص بالبند <a href="{{route('showterm',['id'=>$term->id])}}">{{$term->code}}</a> بمشروع <a href="{{route('showproject',['id'=>$term->project_id])}}">{{$term->project->name}}</a></h3>
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
     <form action="{{route('addtermtransaction',['id'=>$term->id])}}" method="post" id="add_term_transaction">
       <div class="form-group row">
          <label class="control-label col-sm-2 col-md-2 col-lg-2">الكمية السابقة المُنتجة</label>
          <label class="control-label col-sm-10 col-md-10 col-lg-10" id="prev_production" data-prev-amount="{{$prev_production}}" data-value="{{$prev_production*$term->value}}">{{$prev_production}} </label>
       </div>
       <div class="form-group row @if($errors->has("current_production")) has-error @endif">
          <label for="current_production" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية الحالية المُنتجة</label>
          <div class="col-sm-10 col-md-8 col-lg-8">
            <div class="input-group">
              <input type="text" name="current_production" id="current_production" data-term-value="{{$term->value}}" autocomplete="off" class="form-control number" placeholder="أدخل الكمية الحالية المُنتجة" value="{{old('current_production')??$current_production}}">
              <span class="input-group-addon" id="basic-addon1">{{$term->unit}}</span>
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
       <div class="form-group row @if($errors->has("deduction_percent")) has-error @endif">
          <label for="deduction_percent" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة الأستقطاع</label>
          <div class="col-sm-10 col-md-8 col-lg-8">
            <div class="input-group">
              <input type="text" name="deduction_percent" id="deduction_percent" autocomplete="off" class="form-control number" placeholder="أدخل نسبة الأستقطاع" value="{{old("deduction_percent")??$term->deduction_percent}}">
              <span class="input-group-addon" id="basic-addon1">%</span>
            </div>
            @if($errors->has("deduction_percent"))
              @foreach($errors->get("deduction_percent") as $error)
                <span class="help-block">{{ $error }}</span>
              @endforeach
            @endif
          </div>
       </div>
       <div class="form-group row @if($errors->has("deduction_value")) has-error @endif">
          <label for="deduction_value" class="control-label col-sm-2 col-md-2 col-lg-2">قيمة الأستقطاع</label>
          <div class="col-sm-10 col-md-8 col-lg-8">
            <div class="input-group">
              <input type="text" name="deduction_value" id="deduction_value" autocomplete="off" class="form-control number" placeholder="أدخل قيمة الأستقطاع" value="{{old("deduction_value")??$deduction_value}}">
              <span class="input-group-addon" id="basic-addon1">جنيه</span>
            </div>
            @if($errors->has("deduction_value"))
              @foreach($errors->get("deduction_value") as $error)
                <span class="help-block">{{ $error }}</span>
              @endforeach
            @endif
          </div>
       </div>
       <div class="form-group row">
         <label class="control-label col-sm-2 col-md-2 col-lg-2">القيمة قبل الأستقطاع</label>
         <label class="control-label col-sm-10 col-md-10 col-lg-10" id="total_value">{{$net_value+$deduction_value}} جنيه</label>
       </div>
       <div class="form-group row">
          <label class="control-label col-sm-2 col-md-2 col-lg-2">صافى القيمة بعد الأستقطاع</label>
          <label class="control-label col-sm-10 col-md-10 col-lg-10" id="net_value">{{$net_value}} جنيه</label>
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
