@extends('layouts.master')
@section('title','أضافة حساب')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>أضافة حساب</h3>
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
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			<form method="post" action="{{ route('adduser') }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('username')) has-error @endif">
					<label for="username" class="control-label col-sm-2 col-md-2 col-lg-2">أسم المستخدم</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="text" name="username" id="username" value="{{old('username')}}" class="form-control" placeholder="أدخل أسم المستخدم">
						@if($errors->has('username'))
							@foreach($errors->get('username') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('password')) has-error @endif">
					<label for="password" class="control-label col-sm-2 col-md-2 col-lg-2">كلمة المرور</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="password" name="password" id="password" class="form-control" placeholder="أدخل كلمة المرور">
						@if($errors->has('password'))
							@foreach($errors->get('password') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('repassword')) has-error @endif">
					<label for="repassword" class="control-label col-sm-2 col-md-2 col-lg-2">أعادة كلمة المرور</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="password" name="repassword" id="repassword" class="form-control" placeholder="أعادة أدخال كلمة المرور">
						@if($errors->has('repassword'))
							@foreach($errors->get('repassword') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('type')) has-error @endif">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الحساب</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<label>
						<input type="radio" name="type" @if(!old('type') && !isset($cont)) checked @endif @if(old('type')=='admin') checked @endif id="type-admin" value="admin"> مشرف
						</label>
						<label>
						<input type="radio" name="type" @if(old('type')=='contractor') checked @endif id="type-con" value="contractor" @if(isset($cont)) checked @endif> مقاول
						</label>
						@if($errors->has('type'))
							@foreach($errors->get('type') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if(isset($cont)) display @endif @if(old('contractor_id')) display @endif
					@if($errors->has('contractor_id')) has-error display @endif" @if(!isset($cont) && !$errors->has('contractor_id') && !old('contractor_id')) style="display: none" @endif id="contractor-select">
					<label for="name" class="control-label col-sm-2 col-md-2 col-lg-2">أختار المقاول</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						@if(count($contractors)>0)
						<select name="contractor_id" id="contractor_id" class="form-control">
								<option value="0">أختار مقاول</option>
								@foreach($contractors as $contractor)
								@if(old('contractor_id')==$contractor->id)
								<option value="{{$contractor->id}}" selected>{{$contractor->name}}</option>
								@else
								<option value="{{$contractor->id}}">{{$contractor->name}}</option>
								@endif
								@endforeach
						</select>
						@if($errors->has('contractor_id'))
							@foreach($errors->get('contractor_id') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
						@else
						<div class="alert alert-warning">
							لا يوجد مقاوليين
						</div>
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
<script>
	$(document).ready(function(){
		$('#type-admin').change(function(){
			$('#contractor-select').slideUp(1000);
		});
		$('#type-con').change(function(){
			$('#contractor-select').slideDown(1000);
		});
	});
</script>
@endsection
