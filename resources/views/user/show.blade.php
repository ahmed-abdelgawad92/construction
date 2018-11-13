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
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="200px">الأسم بالكامل</th>
							<th>{{$user->name}}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th width="200px">أسم المستخدم</th>
							<td>{{$user->username}}</td>
						</tr>
						<tr>
							<th width="200px">نوع المستخدم</th>
							<td>{{$user->getType()}}</td>
						</tr>
						<tr>
							<th width="200px">تاريخ أنشاء الحساب</th>
							<td>{{date('d/m/Y',strtotime($user->created_at))}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			@if(Auth::user()->id==$user->id)
			<a href="{{ route('updateuser',$user->id) }}" class="btn width-100 float btn-primary">تغيير كلمة السر</a>
			<a class="btn width-100 float btn-default">تعديل</a>
			@endif
			@if(Auth::user()->privilege == 3 && Auth::user()->id!=$user->id)
			@if ($user->enable==1)
				<form class="float" method="post" action="{{ route('enableuser',$user->id) }}">
					<button type="button" data-toggle="modal" data-target="#enable" class="btn width-100 btn-success">تفعيل</button>
					<div class="modal fade" id="enable" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">هل تريد تفعيل الحساب {{$user->name}} ؟</h4>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">لا
									</button>
									<button class="btn btn-success">نعم</button>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="_method" value="PUT">
				</form>
			@else
				<form class="float" method="post" action="{{ route('disableuser',$user->id) }}">
					<button type="button" data-toggle="modal" data-target="#disable" class="btn width-100 btn-dark">تعطيل</button>
					<div class="modal fade" id="disable" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">هل تريد تعطيل الحساب {{$user->name}} ؟</h4>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">لا
									</button>
									<button class="btn btn-dark">نعم</button>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="_method" value="PUT">
				</form>
			@endif
			<form class="float" method="post" action="{{ route('deleteuser',$user->id) }}">
				<button type="button" data-toggle="modal" data-target="#delete" class="btn width-100 btn-danger">حذف</button>
				<div class="modal fade" id="delete" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">هل تريد حذف الحساب {{$user->name}} ؟</h4>
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
	<div class="col-md-4 col-lg-4 col-sm-8 col-sm-offset-2 col-lg-offset-0 col-md-offset-0">
		<div class="panel panel-default">
			<div class="panel-heading project-heading">
				<h4>سجل تعاملات الحساب على النظام</h4>
			</div>
			<div class="panel-body">
				@if(count($logs)>0)
					<h5 class="center mb-5">أخر {{count($logs)}} عمليات قام بها المستخدم</h5>
					@foreach ($logs as $log)
						<div class="bordered-right border-primary p-3">
							<p>نوع العملية: {{$log->getAction()}}</p>
							<p>وصف العملية: {{$log->description}}</p>
							<p>{!!$log->getAffectedRow() ? $log->getAffectedRow()->extractLogLink() : null !!}</p>
						</div>
					@endforeach
				@else
				<div class="alert alert-warning">لا يوجد تعاملات لهذا الحساب على النظام حنى الان</div>
				@endif
			</div>
		</div>
	</div>
</div>
</div>
@endsection
