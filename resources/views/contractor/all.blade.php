@extends('layouts.master')
@section('title')
جميع المقاوليين
@endsection
@section('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3>جميع المقاوليين</h3>
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
			@if(count($contractors)>0)
			<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>أسم المقاول</th>
						<th>نوع المقاولة</th>
						<th>المدينة</th>
						<th>التليفون</th>
					</tr>
				</thead>
				<tbody>
				<?php $count=1;?>
				@foreach($contractors as $contractor)
				<tr>
					<th>{{ $count++ }}</th>
					<th>
						<a href="{{ route('showcontractor',$contractor->id) }}">
						{{ $contractor->name }}
						</a>
					</th>
					<th>{{ $contractor->type }}</th>
					<th>{{ $contractor->city }}</th>
					<th>{{ $contractor->phone }}</th>
				</tr>
				@endforeach
				</tbody>
			</table>
			</div>
			@else
			<div class="alert alert-warning">
				لا يوجد مقاوليين
			</div>
			@endif
			<a href="{{ route('addcontractor') }}" class="btn btn-primary">أضافة مقاول</a>
		</div>
	</div>
</div>
@endsection