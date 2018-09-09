@extends('layouts.master')
@section('title','شراء خامات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة خامات الى المخازن</h3>
		</div>
		<div class="panel-body">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>خطأ</strong>
				</div>
			@endif
			@if(session('update_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('update_error') }}</li>
					</ul>
				</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			<form method="post" action="{{ route('addstore') }}" class="form-horizontal">
				<div class="form-group @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار مشروع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							<option value="0">أختار مشروع</option>
							@foreach($projects as $project)
							<option value="{{$project->id}}">{{$project->name}}</option>
							@endforeach
						</select>
						@if($errors->has('project_id'))
							@foreach($errors->get('project_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('supplier_id')) has-error @endif">
					<label for="supplier_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المقاول المورد</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select  name="supplier_id" id="supplier_id" class="form-control">
							<option value="0">أختار المقاول المورد</option>
							@foreach($suppliers as $supplier)
							<option value="{{$supplier->id}}">{{$supplier->name}}</option>
							@endforeach
						</select>
						@if($errors->has('supplier_id'))
							@foreach($errors->get('supplier_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('type')) has-error @endif">
					<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="type" id="type" class="form-control" placeholder="أدخل نوع الخام" value="{{old('type')}}">
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('amount')) has-error @endif">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="amount" id="amount" class="form-control" placeholder="أدخل الكمية" value="{{old('amount')}}">
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('unit')) has-error @endif">
					<label for="unit" class="control-label col-sm-2 col-md-2 col-lg-2">الوحدة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="unit" id="unit" class="form-control" placeholder="أدخل الوحدة" value="{{old('unit')}}">
						@if($errors->has('unit'))
							@foreach($errors->get('unit') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">القيمة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="value" id="value" class="form-control" placeholder="أدخل القيمة" value="{{old('value')}}">
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('amount_paid')) has-error @endif">
					<label for="amount_paid" class="control-label col-sm-2 col-md-2 col-lg-2">المبلغ المدفوع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="amount_paid" id="amount_paid" class="form-control" placeholder="أدخل المبلغ المدفوع" value="{{old('amount_paid')}}">
						@if($errors->has('amount_paid'))
							@foreach($errors->get('amount_paid') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
		</div>
	</div>
</div>
@endsection
