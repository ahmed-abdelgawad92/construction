@extends('layouts.master')
@section('title','أضافة رسم')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(isset($project))
			<h3>أضافة رسم الى مشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
			@else
			<h3>أضافة رسم</h3>
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
			<form method="post" action="{{ route('addgraph') }}" class="form-horizontal" enctype="multipart/form-data">
				<div class="form-group @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المشروع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							@if(isset($project))
							<option value="{{$project->id}}">{{$project->name}}</option>
							@else
							<option value="">أختار المشروع</option>
							@foreach($projects as $project)
							<option value="{{$project->id}}">{{$project->name}}</option>
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
				<div class="form-group @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الرسم</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم الرسم">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('type')) has-error @endif">
					<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الرسم</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="type" id="type" class="form-control">
							<option value="0" @if(old('type')==0 && old('type')!=null) selected @endif >رسم أنشائى</option>
							<option value="1" @if(old('type')==1 && old('type')!=null) selected @endif >رسم معمارى</option>
						</select>
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group @if($errors->has('graph')) has-error @endif">
					<label for="graph" class="control-label col-sm-2 col-md-2 col-lg-2">أختار ملف الرسم</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="file" name="graph" id="graph" value="{{old('graph')}}" class="form-control">
						@if($errors->has('graph'))
							@foreach($errors->get('graph') as $error)
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
