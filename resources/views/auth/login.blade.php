@extends('layouts.master')
@section('title','تسجيل دخول')
@section('guestcontent')
<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">تسجيل دخول</div>
			<div class="panel-body">
				@if (session('invalid'))
					<div class="alert alert-danger">
						<h2>خطأ</h2>
						<h3>{{session('invalid')}}</h3>
					</div>
				@endif

				<form class="form-horizontal" role="form" id="login_form" method="POST" action="{{ route('postLogin') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="form-group row">
						<label class="col-md-2 offset-md-2 control-label">أسم المستخدم</label>
						<div class="col-md-6">
							<input type="text" class="form-control number" autofocus autocomplete="off" id="username" name="username" value="{{ old('username') }}">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2 offset-md-2 control-label">كلمة المرور</label>
						<div class="col-md-6">
							<input type="password" class="form-control number" id="password" name="password">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-6 offset-md-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember"> تذكرنى
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4 offset-md-4">
							<button type="submit" id="save_btn" class="btn btn-primary form-control">
								دخول
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
