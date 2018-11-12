@extends('layouts.master')
@section('title','جميع الحسابات')
@section('content')
<div class="content">
	@if(session('delete_error'))
	<div class="alert alert-danger">
		<strong>خطأ</strong>
		<br>
		<ul>
			<li>{{ session('delete_error') }}</li>
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
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع حسابات المستخدميين</h3>
		</div>
		<div class="panel-body">
			@if(count($users)>0)
			<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
					<th>#</th>
					<th>الأسم بالكامل</th>
					<th>أسم المستخدم</th>
					<th>نوع الحساب</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($users as $user)
					<tr>
						<th>{{$count++}}</th>
						<th><a href="{{ route('showuser',$user->id) }}">{{$user->name}}</a></th>
						<th><a href="{{ route('showuser',$user->id) }}">{{$user->username}}</a></th>
						<th>{{$user->getType()}}</th>
					</tr>
				@endforeach
				</tbody>
			</table>
			</div>
			@else
			<div class="alert alert-warning">
			<p>لا يوجد حسابات</p>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection
