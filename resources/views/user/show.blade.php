@extends('layouts.master')
@section('title')
حساب {{$user->username}}
@endsection
@section('content')
<div class="content">
<div class="row">
	<div class="col-md-8 col-lg-8 col-sm-8 col-sm-offset-2 col-md-offset-0 col-lg-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading navy-heading">
			<h4>حساب المستخدم {{$user->username}}</h4>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>{{ session('success') }}</strong>
					<br>
				</div>
			@endif
			@if(Auth::user()->id==$user->id)
			<a href="{{ route('updateuser',$user->id) }}" class="btn width-100 float btn-default">تعديل</a>
			@endif
			@if(Auth::user()->type=='admin'&& Auth::user()->id!=$user->id)
			<form class="float" method="post" action="{{ route('deleteuser',$user->id) }}">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn width-100 btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف الحساب {{$user->name}}</h4>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">لا
								</button>
								<button class="btn btn-danger">نعم</button>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="_method" value="DELETE">
			</form>
			@endif
		</div>
	</div>
	</div>
	@if(Auth::user()->type=='admin')
	<div class="col-md-4 col-lg-4 col-sm-8 col-sm-offset-2 col-lg-offset-0 col-md-offset-0">
	<div class="panel panel-default">
		<div class="panel-heading project-heading">
			<h4>سجل تعاملات الحساب على النظام</h4>
		</div>
		<div class="panel-body">
		</div>
	</div>
	</div>
	@endif
</div>
</div>
@endsection
