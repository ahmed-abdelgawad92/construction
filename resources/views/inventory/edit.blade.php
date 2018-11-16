@extends('layouts.master')
@section('title','تعديل ملف الحصر '.$inventory->name)
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تعديل ملف حصر '{{$inventory->name}}' بمشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
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
			<form method="post" action="{{ route('updateinventory',['id'=>$project->id]) }}" class="form-horizontal" id="add_inventory">
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم ملف الحصر *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{$inventory->name}}" class="form-control" placeholder="أدخل أسم ملف الحصر">
						@if($errors->has('name'))
							@foreach($errors->get('name') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('description')) has-error @endif">
					<label for="description" class="control-label col-sm-2 col-md-2 col-lg-2">تفاصيل الحصر</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<textarea name="description" id="description" class="form-control" placeholder="تفاصيل عن الحصر">{{$inventory->description}}</textarea>
						@if($errors->has('description'))
							@foreach($errors->get('description') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-default form-control" id="save_btn">تعديل</button>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
        @method('PUT')
			</form>
		</div>
	</div>
</div>
@endsection
