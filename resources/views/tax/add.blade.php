@extends('layouts.master')
@section('title','أضافة أستقطاع')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(isset($project))
			<h3>أضافة أستقطاع الى مشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
			@else
			<h3>أضافة أستقطاع</h3>
			@endif
		</div>
		<div class="panel-body">
			@if(session('insert_error'))
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<br>
					<ul>
						<li>{{ session('insert_error') }}</li>
					</ul>
				</div>
			@endif
			<form method="post" action="{{ route('addtax') }}" class="form-horizontal" id="add_tax">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المشروع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							@if(isset($project))
							<option value="{{$project->id}}">{{$project->name.' - '.$project->city}}</option>
							@else
							<option value="">أختار المشروع</option>
							@foreach($projects as $project)
							<option value="{{$project->id}}">{{$project->name.' - '.$project->city}}</option>
							@endforeach
							@endif
						</select>
						@if($errors->has('project_id'))
							@foreach($errors->get('project_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الأستقطاع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم هذه الأستقطاع">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة الأستقطاع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group w-100">
						<input type="text" name="value" id="value" value="{{old('value')}}" class="form-control input-right" style="width:85% !important;" placeholder="أدخل قيمة أو نسبة الأستقطاع">
						<select class="form-control input-left" style="width:15% !important;" name="type" id="type">
							<option value="1">%</option>
							<option value="2">جنيه</option>
						</select>
						</div>
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
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
		</div>
	</div>
</div>
@endsection
