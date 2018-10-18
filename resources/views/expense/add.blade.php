@extends('layouts.master')
@section('title','أضافة أكراميات')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(isset($project))
			<h3>أضافة أكراميات الى مشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
			@else
			<h3>أضافة أكرامية</h3>
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
			<form method="post" action="{{ route('addexpense') }}" id="add_expense" class="form-horizontal">
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
				<div class="form-group row @if($errors->has('whom')) has-error @endif">
					<label for="whom" class="control-label col-sm-2 col-md-2 col-lg-2">وصف الأكرامية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="whom" id="whom" value="{{old('whom')}}" class="form-control" placeholder="أدخل وصف هذه الأكرامية">
						@if($errors->has('whom'))
							@foreach($errors->get('whom') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('expense')) has-error @endif">
					<label for="expense" class="control-label col-sm-2 col-md-2 col-lg-2">قيمة الأكرامية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group" id="expense_group">
						<input type="text" name="expense" id="expense" value="{{old('expense')}}" class="form-control number" placeholder="أدخل قيمة الأكرامية">
						<span class="input-group-addon" id="basic-addon1">جنيه</span>
						</div>
						@if($errors->has('expense'))
							@foreach($errors->get('expense') as $error)
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
