@extends('layouts.master')
@section('title','تغيير كلمة المرور')
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>تغيير كلمة المرور حساب المستخدم {{$user->username}}</h3>
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
			<form method="post" action="{{ route('updateuser',$user->id) }}" class="form-horizontal">
				<div class="form-group row @if($errors->has('oldpassword')) has-error @endif">
					<label for="oldpassword" class="control-label col-sm-2 col-md-2 col-lg-2">كلمة المرور الحالية</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="password" name="oldpassword" id="oldpassword" class="form-control" placeholder="أدخل كلمة المرور الحالية">
						@if($errors->has('oldpassword'))
							@foreach($errors->get('oldpassword') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('password')) has-error @endif">
					<label for="password" class="control-label col-sm-2 col-md-2 col-lg-2">كلمة المرور الجديدة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="password" name="password" id="password" class="form-control" placeholder="أدخل كلمة المرور الجديدة">
						@if($errors->has('password'))
							@foreach($errors->get('password') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="form-group row @if($errors->has('repassword')) has-error @endif">
					<label for="repassword" class="control-label col-sm-2 col-md-2 col-lg-2">أعادة كلمة المرور الجديدة</label>
					<div class="col-sm-8 col-md-8 col-lg-8">
						<input type="password" name="repassword" id="repassword" class="form-control" placeholder="أعادة أدخال كلمة المرور الجديدة">
						@if($errors->has('repassword'))
							@foreach($errors->get('repassword') as $error)
								<span class="help-block">{{ $error }}</span>
							@endforeach
						@endif
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 offset-sm-5 offset-md-5 offset-lg-5">
					<button class="btn btn-primary form-control" id="save_btn">تغيير كلمة المرور</button>
				</div>
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
			</form>
		</div>
	</div>
</div>
@endsection
