@extends('layouts.master')
@section('title','تعديل أستهلاك')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل أستهلاك للبند <a href="{{ route('showterm',$consumption->term_id) }}">{{$consumption->term->code}}</a> بمشروع <a href="{{ route('showproject',$consumption->term->project->id) }}">{{$consumption->term->project->name}}</a></h3>
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
			@if(session('amount_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('amount_error') }} <a href="{{url("store/add/0",$consumption->term->project->id)}}" class="btn btn-danger">أضافة خامات</a></li>
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
			<form method="post" action="{{ route('updateconsumption',$consumption->id) }}" class="form-horizontal">
				<div class="form-group @if($errors->has('type')) has-error @endif">
					<label for="type_consumption" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select id="type_consumption" name="type" class="form-control">
						<option value="0">أختار نوع الخام المستهلك</option>
						@foreach($store_types as $type)
						@if($consumption->type==$type->name)
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
				<div class="form-group @if($errors->has('amount')) has-error @endif">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
							<input type="text" name="amount" id="amount" value="{{$consumption->amount}}" class="form-control" placeholder="أدخل الكمية" aria-describedby="basic-addon1">
							<span class="input-group-addon" id="basic-addon1">{{$unit->unit}}</span>
						</div>
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
					<button class="btn btn-primary form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="PUT">
			</form>
		</div>
	</div>
</div>
@endsection
