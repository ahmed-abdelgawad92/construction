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
			<form method="post" action="{{ route('addtax') }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
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
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم الأستقطاع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم هذه الضريبة">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('percent')) has-error @endif">
					<label for="percent" class="control-label col-sm-2 col-md-2 col-lg-2">نسبة الأستقطاع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
						<input type="text" name="percent" id="percent" value="{{old('percent')}}" class="form-control" placeholder="أدخل نسبة الضريبة">
						<span class="input-group-addon" id="basic-addon1">%</span>
						</div>
						@if($errors->has('percent'))
							@foreach($errors->get('percent') as $error)
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
