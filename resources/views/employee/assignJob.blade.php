@extends('layouts.master')
@section('title','تعيين الموظف بمشروع')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعيين الموظف <a href="{{ route('showemployee',$employee->id) }}">{{$employee->name}}</a></h3>
		</div>
		<div class="panel-body">
			@if(session('insert_error'))
			<div class="alert alert-danger">
				{{session('insert_error')}}
			</div>
			@endif
			<form method="post" action="{{ route('assignjob',$employee->id) }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="project_id" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المشروع</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
						<option>أختار المشروع</option>
						@foreach($projects as $project)
						<option @if(old('project_id')==$project->id) selected @endif value="{{$project->id}}">
						{{$project->name}}
						</option>
						@endforeach
						</select>
						@if($errors->has('project_id'))
							@foreach($errors->get('project_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('salary')) has-error @endif">
					<label for="salary" class="control-label col-sm-2 col-md-2 col-lg-2">الراتب</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="salary" id="salary" value="{{old('salary')}}" class="form-control" placeholder="أدخل الراتب">
						@if($errors->has('salary'))
							@foreach($errors->get('salary') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">تعيين</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
		</div>
	</div>
</div>
@endsection
