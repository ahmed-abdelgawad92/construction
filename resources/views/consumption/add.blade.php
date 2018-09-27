@extends('layouts.master')
@section('title','أضافة أستهلاك')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة أستهلاك جديد للبند <a href="{{ route('showterm',$term->id) }}">{{$term->code}}</a> بمشروع <a href="{{ route('showproject',$term->project->id) }}">{{$term->project->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>خطأ</strong>
			</div>
			@endif
			@if(session('insert_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('insert_error') }}</li>
					</ul>
				</div>
			@endif
			@if(session('amount_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('amount_error') }} <a href="{{route('addstores',['cid'=>0,'pid'=>$term->project_id,'tid'=>$term->id])}}" class="btn btn-danger">أضافة خامات</a></li>
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
			@if(count($store_types)>0)
			<form method="post" action="{{ route('addconsumption',$term->id) }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('type')) has-error @endif">
					<label for="type_consumption" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select id="type_consumption" name="type" class="form-control">
						<option value="0">أختار نوع الخام المستهلك</option>
						@foreach($store_types as $type)
						@if(old('type')==$type->name)
						<option value="{{$type->name}}" selected>{{$type->name}}</option>
						@else
						<option value="{{$type->name}}">{{$type->name}}</option>
						@endif
						@endforeach
						</select>
						@foreach($store_types as $type)
						<input type="hidden" name="{{$type->name}}" value="{{$type->unit}}">
						@endforeach
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if(old('type')!=0 && old('type')!=null) display @endif @if($errors->has('amount')) has-error @endif" id="amount_cons">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
							<input type="text" name="amount" id="amount" value="{{old('amount')}}" class="form-control" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
							<span class="input-group-addon" id="basic-addon1"></span>
						</div>
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">حفظ</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
			@else
			<div class="alert alert-warning">
				لا يوجد أنواع خامات , من فضلك أضف أنواع للخاماات <a href="{{ route('addstoretype') }}" class="btn btn-warning">أضافة نوع خام جديد</a>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
