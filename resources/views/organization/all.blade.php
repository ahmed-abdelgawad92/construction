@extends('layouts.master')
@section('title','جميع العملاء')
@section('content')
<div class="content">
	<div class="panel panel-default">
	<div class="panel-heading">
		@if(Route::current()->getName()=='allorganization')
		<h3>جميع العملاء</h3>
		@elseif(Route::current()->getName()=='allclients')
		<h3>جميع العملاء (بدون نسبة)</h3>
		@elseif(Route::current()->getName()=='allcontractclients')
		<h3>جميع المقاوليين العملاء</h3>
		@endif
	</div>
	<div class="panel-body">
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
	@if(count($organizations)>0)
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>أسم العميل</th>
				<th>الشارع</th>
				<th>المركز</th>
				<th>المدينة</th>
				<th>التليفون</th>
				<th>نوع العميل</th>
			</tr>
		</thead>
		<tbody>
				<?php $count=1; ?>
			@foreach($organizations as $org)
				<tr>
					<th>{{$count}}</th>
					<td><a href="{{ route('showorganization',$org->id) }}" class="btn btn-link">{{$org->name}}</a></td>
					<td>{{$org->address}}</td>
					<td>{{$org->center}}</td>
					<td>{{$org->city}}</td>
					<td>{{$org->phone}}</td>
					@if($org->type==0)
					<td>عميل</td>
					@elseif($org->type==1)
					<td>مقاول</td>
					@endif
				</tr>
				<?php $count++;?>
			@endforeach
		</tbody>
	</table>
	</div>
	@else
		<div class="alert alert-warning">لا يوجد عملاء </div>
	@endif
	<a href="{{ route('addorganization') }}" class="btn btn-primary">أضافة عميل</a>
	</div>
	</div>
</div>
@endsection()
