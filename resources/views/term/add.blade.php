@extends('layouts.master')
@section('title','أضافة بند')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(count($projects)==1)
			<h3>
			أضافة بند الى مشروع <a href="{{ route('showproject',$projects[0]->id) }}">{{$projects[0]->name}}</a>
			</h3>
			@else
			<h3>أضافة بند</h3>
			@endif
		</div>
		<div class="panel-body">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>خطأ</strong>
					<ul>
					@foreach ($errors as $err)
						<li>{{$err}}</li>
					@endforeach
					</ul>
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
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			@if(count($projects)>0)
			<form method="post" action="{{ route('addterm') }}" id="add_term" class="form-horizontal">
				<div class="form-group row @if($errors->has('project_id')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">تابع للمشروع *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<select name="project_id" id="project_id" class="form-control">
							@if(count($projects)==1)
								<option value="{{$projects[0]->id}}">{{$projects[0]->name}}</option>
							@else
								<option value="0">أختار مشروع</option>
								@foreach($projects as $project)
								<option @if (old('project_id')) selected @endif value="{{$project->id}}">{{$project->name}}</option>
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
				<div class="form-group row @if($errors->has('type_select')||$errors->has('type_text')) has-error @endif">
					<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع البند *</label>
					<div class="col-sm-8 col-md-8 col-lg-8" >
						<div class="input-group w-100" id="type">
						@if (count($term_types)>0)
						<select name="type_select" id="type_select" class="form-control input-right">
							<option value="">أختار نوع البند</option>
							@foreach($term_types as $type)
							<option @if($type->name==old('type_select')) selected @endif value="{{$type->name}}">
							{{$type->name}}
							</option>
							@endforeach
						</select>
						@endif
						<input type="text" name="type_text" id="type_text" value="{{old('type_text')}}" placeholder="ادخل نوع البند" class="form-control @if (count($term_types)>0)	input-left @endif ">
						@if($errors->has('type_text'))
							@foreach($errors->get('type_text') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
						@if($errors->has('type_select'))
							@foreach($errors->get('type_select') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
						</div>
					</div>
				</div>
				<div class="form-group row @if($errors->has('code')) has-error @endif">
					<label for="code" class="control-label col-sm-2 col-md-2 col-lg-2">كود البند *</label>
					<div class="col-sm-8 col-md-8 col-lg-8" id="get_statement">
						<input type="text" name="code" id="code" class="form-control number" placeholder="أدخل كود البند" value="{{old('code')}}">
						@if($errors->has('code'))
							@foreach($errors->get('code') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('statement')) has-error @endif">
					<label for="statement" class="control-label col-sm-2 col-md-2 col-lg-2">بيان الأعمال *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<textarea name="statement" id="statement" class="form-control" placeholder="أدخل بيان الأعمال">{{old('statement')}}</textarea>
						@if($errors->has('statement'))
							@foreach($errors->get('statement') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('unit')) has-error @endif">
					<label for="unit" class="control-label col-sm-2 col-md-2 col-lg-2">الوحدة *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="unit" id="unit" class="form-control" placeholder="أدخل الوحدة" value="{{old('unit')}}">
						@if($errors->has('unit'))
							@foreach($errors->get('unit') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('amount')) has-error @endif">
					<label for="amount" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="amount" id="amount" class="form-control number" placeholder="أدخل الكمية" value="{{old('amount')}}">
						@if($errors->has('amount'))
							@foreach($errors->get('amount') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('value')) has-error @endif">
					<label for="value" class="control-label col-sm-2 col-md-2 col-lg-2">القيمة *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="value" id="value" class="form-control number" placeholder="أدخل القيمة" value="{{old('value')}}">
						@if($errors->has('value'))
							@foreach($errors->get('value') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('num_phases')) has-error @endif">
					<label for="num_phases" class="control-label col-sm-2 col-md-2 col-lg-2">عدد المراحل</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="num_phases" id="num_phases" class="form-control number" placeholder="أدخل عدد المراحل" value="{{old('num_phases')}}">
						@if($errors->has('num_phases'))
							@foreach($errors->get('num_phases') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('started_at')) has-error @endif">
					<label for="started_at" class="control-label col-sm-2 col-md-2 col-lg-2">تاريخ البدء</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="started_at" id="started_at" autocomplete="off" value="{{old('started_at')}}" class="form-control" placeholder="أدخل تاريخ استلام الموقع">
						@if($errors->has('started_at'))
							@foreach($errors->get('started_at') as $error)
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
			لا يوجد مشروعات

		</div>
		@endif
		</div>
	</div>
</div>
@endsection
