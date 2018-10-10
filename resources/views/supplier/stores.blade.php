@extends('layouts.master')
@section('title','جميع واردات المخازن')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع واردات المخازن من المورد <a href="{{ route('showsupplier',$supplier->id) }}">{{$supplier->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('insert_error'))
			<div class="alert alert-danger">
				{{session('insert_error')}}
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success">
				{{session('success')}}
			</div>
			@endif
			@if(count($stores)>0)
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>نوع الخام</th>
							<th>كمية</th>
							<th>قيمة الوحدة</th>
							<th>أجمالى المدفوع</th>
							<th>أجمالى الباقى</th>
							<th>أجمالى القيمة</th>
							<th>تابع لمشروع</th>
							<th>تاريخ التوريد</th>
							<th>أمر</th>
						</tr>
					</thead>
					<tbody>
					<?php $count=1;?>
					@foreach($stores as $store)
						<tr>
							<th>{{$count++}}</th>
							<th>{{$store->type}}</th>
							<th>{{number_format(max($store->amount,0))}} {{$store->unit}}</th>
							<th>{{number_format(max($store->value,0))}} جنيه</th>
							<th>{{number_format(max($store->amount_paid,0))}} جنيه</th>
							<th>{{number_format(max(($store->amount*$store->value)-$store->amount_paid,0))}} جنيه</th>
							<th>{{number_format(max($store->amount*$store->value,0))}} جنيه</th>
							<th><a href="{{ route('showproject',$store->project->id) }}">{{$store->project->name}}</a></th>
							<th>{{date("d/m/Y",strtotime($store->created_at))}}</th>
							<th>
								@if(($store->amount*$store->value)>$store->amount_paid)
								<a href="{{route("addPaymentToStore",['id'=>$store->id])}}" data-allowed-amount="{{($store->amount*$store->value)-$store->amount_paid}}" class="btn btn-success add_payment_to_store">أدفع</a>
								@else
								<div class="state">خالص</div>
								@endif
								<a href="{{route("updatestore",['id'=>$store->id])}}" class="btn btn-default">تعديل</a>
								<a href="{{route("deletestore",['id'=>$store->id])}}" class="btn btn-danger note_delete">حذف</a>
							</th>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد واردات
			</div>
			@endif
		</div>
	</div>
</div>
<div id="float_container">
	<div id="float_form_container">
		<span class="close">&times;</span>
		<form action="" method="post" class="float_form" id="add_payment_to_store">
			<div class="form-group @if($errors->has('payment')) has-error @endif">
				<label for="payment" class="control-label">المبلغ</label>
				<div>
					<input autocomplete="off" autofocus type="text" name="payment" id="payment" class="form-control" placeholder="أدخل المبلغ المراد دفعه" value="{{old('payment')}}"/>
				</div>
					@if($errors->has('payment'))
						@foreach($errors->get('payment') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
			</div>
			<div class="form-group @if($errors->has('payment_type')) has-error @endif">
				<label for="payment_type" class="control-label">طريقة الدفع</label>
				<div>
					<label><input type="radio" name="payment_type" @if(!old("payment_type")||old("payment_type")==0) checked @endif value="0"> صندوق</label>
					<label><input type="radio" name="payment_type" @if(old("payment_type")==1) checked @endif value="1"> قرض</label>
				</div>
					@if($errors->has('payment_type'))
						@foreach($errors->get('payment_type') as $error)
							<span class="help-block">{{ $error }}</span>
						@endforeach
					@endif
			</div>
			<button class="btn btn-success form-control" id="save_btn">أدفع</button>
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			@method("PUT")
		</form>
		<div class="float_form" id="delete_note">
			<h4>هل تريد فعلا حذف هذه الكمية الواردة من الخام؟</h4>
			<button class="btn btn-default btn-close">لا</button>
			<a href="" class="btn btn-danger">نعم</a>
		</div>
	</div>
</div>
@endsection
