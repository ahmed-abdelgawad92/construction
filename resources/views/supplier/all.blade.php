@extends('layouts.master')
@section('title')
جميع الموردين
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع الموردين</h3>
		</div>
		<div class="panel-body">
			@if(session('success'))
				<div class="alert alert-success">
					<strong>تمت بنجاح</strong>
					<br>
					<ul>
						<li>{{ session('success') }}</li>
					</ul>
				</div>
			@endif
			@if(count($suppliers)>0)
			<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>أسم المورد</th>
						<th>نوع المورد</th>
						<th>المدينة</th>
						<th>التليفون</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($suppliers as $supplier)
				<tr>
					<th>{{ $count++ }}</th>
					<th>
						<a href="{{ route('showsupplier',$supplier->id) }}">
						{{ $supplier->name }}
						</a>
					</th>
					<th>{{ $supplier->type }}</th>
					<th>{{ $supplier->city }}</th>
					<th>{{ $supplier->phone }}</th>
				</tr>
				@endforeach
				</tbody>
			</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد موردين
			</div>
			@endif
			<a href="{{ route('addsupplier') }}" class="btn btn-primary">أضافة مورد</a>
		</div>
	</div>
</div>
@endsection