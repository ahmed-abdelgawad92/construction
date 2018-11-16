@extends('layouts.master')
@section('title','أضافة ملف حصر لمشروع '.$project->name)
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة ملف حصر الى مشروع <a href="{{ route('showproject',$project->id) }}">{{$project->name}}</a></h3>
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
			<form method="post" action="{{ route('addinventory',['id'=>$project->id]) }}" class="form-horizontal" enctype="multipart/form-data" id="add_inventory">
				<div class="form-group row @if($errors->has('name')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أسم ملف الحصر *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="أدخل أسم ملف الحصر">
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
						<textarea name="description" id="description" class="form-control" placeholder="تفاصيل عن الحصر">{{old('description')}}</textarea>
						@if($errors->has('description'))
							@foreach($errors->get('description') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('file')) has-error @endif">
					<label for="file" class="control-label col-sm-2 col-md-2 col-lg-2">أختار ملف الحصر *</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<div class="input-group" id="graph_group">
						  <input type="text" class="form-control" id="file_name" value="{{old("file")}}"  placeholder="اختار ملف الحصر" aria-describedby="basic-addon2">
						  <span class="input-group-addon" id="basic-addon2">اختار الملف</span>
						</div>
						<input type="file" name="file" id="file" value="{{old('file')}}" ondragleave="drop(event)" ondragover="drag(event)" class="form-control file">
						@if($errors->has('file'))
							@foreach($errors->get('file') as $error)
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
